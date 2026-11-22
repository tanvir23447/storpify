<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add STORPIFY fields to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('role')->default('customer')->index()->after('phone'); // super_admin, general_admin, moderator, customer
            $table->string('status')->default('active')->index()->after('role'); // active, suspended, pending
            $table->boolean('two_factor_enabled')->default(false)->after('status');
            $table->string('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->timestamp('last_login_at')->nullable()->after('two_factor_secret');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->json('assigned_categories')->nullable()->after('last_login_ip'); // Category-scoped access for moderator/staff
        });

        // Roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        // Permissions table
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key')->unique();
            $table->string('module')->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // User roles mapping
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'role_id']);
        });

        // Role permissions mapping
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']);
        });

        // User permission direct overrides
        Schema::create('user_permission_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_granted')->default(true);
            $table->timestamps();
            $table->unique(['user_id', 'permission_id']);
        });

        // User profiles
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('avatar')->nullable();
            $table->string('company')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('Bangladesh');
            $table->string('postal_code')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });

        // Staff Invitations
        Schema::create('staff_invitations', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('token')->unique();
            $table->string('role');
            $table->json('assigned_permissions')->nullable();
            $table->foreignId('invited_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });

        // Login histories
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('login_at');
            $table->string('status')->default('success'); // success, failed
        });

        // Audit logs (IMMUTABLE, NO CASCADE DELETE)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->nullable()->index();
            $table->string('action'); // create, update, delete, status_change, etc.
            $table->string('module')->index();
            $table->string('record_id')->nullable()->index();
            $table->json('old_value')->nullable();
            $table->json('new_value')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // Security events
        Schema::create('security_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('event_type'); // unauthorized_access, brute_force, permission_denied
            $table->string('ip_address', 45)->nullable();
            $table->string('severity')->default('warning'); // info, warning, critical
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('security_events');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('login_histories');
        Schema::dropIfExists('staff_invitations');
        Schema::dropIfExists('user_profiles');
        Schema::dropIfExists('user_permission_overrides');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'role', 'status', 'two_factor_enabled',
                'two_factor_secret', 'last_login_at', 'last_login_ip', 'assigned_categories'
            ]);
        });
    }
};
