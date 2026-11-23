<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateAdminCommand extends Command
{
    protected $signature = 'storpify:create-admin 
                            {--email= : The email address for the Super Admin}
                            {--password= : The password for the Super Admin}
                            {--name= : The name of the Super Admin}';

    protected $description = 'Create or update a Super Admin account with full platform control';

    public function handle(): int
    {
        $this->info('====================================================');
        $this->info('STORPIFY — SUPER ADMIN CREATION WIZARD');
        $this->info('====================================================');

        $name = $this->option('name') ?: $this->ask('Enter Super Admin Name', 'Super Administrator');
        $email = $this->option('email') ?: $this->ask('Enter Super Admin Email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address provided.');
            return 1;
        }

        $password = $this->option('password') ?: $this->secret('Enter Super Admin Password (min 8 characters)');

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return 1;
        }

        // Find or create super_admin role
        $role = Role::firstOrCreate(['slug' => 'super_admin'], [
            'name' => 'Super Administrator',
            'description' => 'Unrestricted platform control',
            'is_system' => true,
        ]);

        $user = User::where('email', $email)->first();

        if ($user) {
            if ($this->confirm("User [{$email}] already exists. Do you want to promote/reset to Super Admin with this password?", true)) {
                $user->update([
                    'name' => $name,
                    'password' => Hash::make($password),
                    'role' => 'super_admin',
                    'status' => 'active',
                    'email_verified_at' => $user->email_verified_at ?: now(),
                ]);
                $user->roles()->syncWithoutDetaching([$role->id]);
                $this->info("✓ Successfully updated [{$email}] to Super Admin!");
            } else {
                $this->warn('Operation cancelled.');
                return 0;
            }
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'super_admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            $user->roles()->sync([$role->id]);

            UserProfile::firstOrCreate(['user_id' => $user->id], [
                'bio' => 'Lead Administrator & Founder of STORPIFY',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
            ]);

            $this->info("✓ Successfully created new Super Admin: [{$email}]!");
        }

        $this->newLine();
        $this->info("Login URL: " . config('app.url') . "/login");
        $this->info("Email:     {$email}");
        $this->info("Role:      Super Admin (Full RBAC Bypass)");
        $this->info('====================================================');

        return 0;
    }
}
