<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\ProductCategory;
use App\Models\AuditLog;
use App\Models\SecurityEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    protected function checkSuperAdmin(): void
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Access Denied: Only Super Admin can manage staff and access control.');
        }
    }

    public function index()
    {
        $this->checkSuperAdmin();

        $staff = User::whereIn('role', ['super_admin', 'general_admin', 'moderator'])
            ->with(['roles', 'profile'])
            ->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $this->checkSuperAdmin();

        $roles = Role::whereIn('slug', ['general_admin', 'moderator'])->get();
        $permissions = Permission::all()->groupBy('module');
        $categories = ProductCategory::active()->get();

        return view('admin.staff.create', compact('roles', 'permissions', 'categories'));
    }

    public function store(Request $request)
    {
        $this->checkSuperAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:general_admin,moderator', // Cannot create super_admin
            'assigned_categories' => 'nullable|array',
            'permission_ids' => 'nullable|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
            'email_verified_at' => now(),
            'assigned_categories' => $request->assigned_categories ?: [],
        ]);

        $role = Role::where('slug', $request->role)->first();
        if ($role) {
            $user->roles()->sync([$role->id]);
        }

        // Direct permission overrides
        if ($request->filled('permission_ids')) {
            foreach ($request->permission_ids as $pId) {
                \App\Models\UserPermissionOverride::updateOrCreate(
                    ['user_id' => $user->id, 'permission_id' => $pId],
                    ['is_granted' => true]
                );
            }
        }

        AuditLog::record('create_staff', 'staff', (string)$user->id, null, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'নতুন স্টাফ মেম্বার সফলভাবে যুক্ত হয়েছে।');
    }

    public function edit(int $id)
    {
        $this->checkSuperAdmin();

        $staffMember = User::with(['roles', 'permissionOverrides'])->findOrFail($id);

        if ($staffMember->id === auth()->id()) {
            return redirect()->route('admin.staff.index')->with('error', 'You cannot modify your own administrative role or permissions.');
        }

        $roles = Role::whereIn('slug', ['general_admin', 'moderator'])->get();
        $permissions = Permission::all()->groupBy('module');
        $categories = ProductCategory::active()->get();

        return view('admin.staff.edit', compact('staffMember', 'roles', 'permissions', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $this->checkSuperAdmin();

        $staffMember = User::findOrFail($id);

        if ($staffMember->id === auth()->id()) {
            abort(403, 'Self role-escalation is strictly prohibited by security policies.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:general_admin,moderator',
            'status' => 'required|in:active,suspended',
            'assigned_categories' => 'nullable|array',
            'permission_ids' => 'nullable|array',
        ]);

        $oldData = $staffMember->toArray();

        $staffMember->update([
            'name' => $request->name,
            'role' => $request->role,
            'status' => $request->status,
            'assigned_categories' => $request->assigned_categories ?: [],
        ]);

        $role = Role::where('slug', $request->role)->first();
        if ($role) {
            $staffMember->roles()->sync([$role->id]);
        }

        // Reset and set overrides
        \App\Models\UserPermissionOverride::where('user_id', $staffMember->id)->delete();
        if ($request->filled('permission_ids')) {
            foreach ($request->permission_ids as $pId) {
                \App\Models\UserPermissionOverride::create([
                    'user_id' => $staffMember->id,
                    'permission_id' => $pId,
                    'is_granted' => true,
                ]);
            }
        }

        AuditLog::record('update_staff', 'staff', (string)$staffMember->id, $oldData, $staffMember->toArray());

        return redirect()->route('admin.staff.index')->with('success', 'স্টাফ রোল ও পারমিশন সফলভাবে আপডেট হয়েছে।');
    }

    public function auditLogs()
    {
        $this->checkSuperAdmin();

        $logs = AuditLog::with('staff')->latest()->paginate(25);
        $securityEvents = SecurityEvent::with('user')->latest()->take(10)->get();

        return view('admin.audit-logs', compact('logs', 'securityEvents'));
    }
}
