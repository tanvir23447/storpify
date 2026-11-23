<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Core Roles
        $superAdminRole = Role::firstOrCreate(['slug' => 'super_admin'], [
            'name' => 'Super Admin',
            'description' => 'Full and unrestricted platform control',
            'is_system' => true,
        ]);

        $generalAdminRole = Role::firstOrCreate(['slug' => 'general_admin'], [
            'name' => 'General Admin',
            'description' => 'Managerial control with assigned permissions',
            'is_system' => true,
        ]);

        $moderatorRole = Role::firstOrCreate(['slug' => 'moderator'], [
            'name' => 'Moderator',
            'description' => 'Restricted operational staff access',
            'is_system' => true,
        ]);

        $customerRole = Role::firstOrCreate(['slug' => 'customer'], [
            'name' => 'Customer',
            'description' => 'Registered storefront and subscription customer',
            'is_system' => true,
        ]);

        // 2. Create Permissions from Config
        $permissionDefs = config('storpify.permissions', []);
        $allPermissionIds = [];

        foreach ($permissionDefs as $key => $name) {
            $parts = explode('.', $key);
            $module = $parts[0] ?? 'general';

            $perm = Permission::firstOrCreate(['key' => $key], [
                'name' => $name,
                'module' => $module,
                'description' => "Permission to {$name}",
            ]);

            $allPermissionIds[] = $perm->id;
        }

        // Assign all permissions to Super Admin role
        $superAdminRole->permissions()->sync($allPermissionIds);

        // General Admin gets store, order, customer, subscription, cms permissions (except staff management and system settings)
        $generalAdminPerms = Permission::whereNotIn('module', ['staff', 'system'])->pluck('id');
        $generalAdminRole->permissions()->sync($generalAdminPerms);

        // Moderator gets product.view, product.create, product.update, category.view, order.view, subscription.view
        $modPerms = Permission::whereIn('key', [
            'product.view', 'product.create', 'product.update', 'product.image.upload',
            'category.view', 'order.view', 'payment.view',
            'subscription.view', 'homepage.view'
        ])->pluck('id');
        $moderatorRole->permissions()->sync($modPerms);

        // 3. Create System Roles & Seed Super Admin (if configured)
        $superAdminPassword = env('SUPER_ADMIN_PASSWORD') 
            ? Hash::make(env('SUPER_ADMIN_PASSWORD')) 
            : Hash::make(\Illuminate\Support\Str::random(32));

        // Super Admin
        $superAdmin = User::firstOrCreate(['email' => env('SUPER_ADMIN_EMAIL', 'admin@storpify.com')], [
            'name' => 'Super Administrator',
            'password' => $superAdminPassword,
            'phone' => '01700000001',
            'role' => 'super_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $superAdmin->roles()->sync([$superAdminRole->id]);
        UserProfile::firstOrCreate(['user_id' => $superAdmin->id], [
            'bio' => 'Lead Administrator & Founder of STORPIFY',
            'company' => 'STORPIFY BD',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
        ]);

        // General Admin
        $generalAdmin = User::firstOrCreate(['email' => 'manager@storpify.com'], [
            'name' => 'Store Operations Manager',
            'password' => Hash::make(\Illuminate\Support\Str::random(32)),
            'phone' => '01700000002',
            'role' => 'general_admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $generalAdmin->roles()->sync([$generalAdminRole->id]);
        UserProfile::firstOrCreate(['user_id' => $generalAdmin->id], [
            'bio' => 'Senior Operations Manager',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
        ]);

        // Moderator
        $moderator = User::firstOrCreate(['email' => 'moderator@storpify.com'], [
            'name' => 'Content Moderator',
            'password' => Hash::make(\Illuminate\Support\Str::random(32)),
            'phone' => '01700000003',
            'role' => 'moderator',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $moderator->roles()->sync([$moderatorRole->id]);
        UserProfile::firstOrCreate(['user_id' => $moderator->id], [
            'bio' => 'Digital Content Reviewer',
            'city' => 'Chittagong',
            'country' => 'Bangladesh',
        ]);

        // Sample Customer
        $customer = User::firstOrCreate(['email' => 'customer@storpify.com'], [
            'name' => 'রাকিবুল হাসান (Rakibul Hasan)',
            'password' => Hash::make(\Illuminate\Support\Str::random(32)),
            'phone' => '01812345678',
            'role' => 'customer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $customer->roles()->sync([$customerRole->id]);
        UserProfile::firstOrCreate(['user_id' => $customer->id], [
            'bio' => 'Web Developer & Digital Entrepreneur',
            'city' => 'Dhaka',
            'country' => 'Bangladesh',
        ]);
    }
}
