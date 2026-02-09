<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get or create roles
        $adminRole = Role::where('name', 'Admin')->first();
        $creatorRole = Role::where('name', 'Creator')->first();
        $viewerRole = Role::where('name', 'Viewer')->first();

        // Get default package
        $defaultPackage = Package::first();

        // Create one Admin user
        $admin = User::create([
            'name' => 'Test',
            'last_name' => 'Admin',
            'email' => 'testadmin@test.com',
            'password' => Hash::make('password'),
            'phone' => '+1-555-0001',
            'status' => 1,
            'email_verified_at' => now(),
            'designation' => 'Test Administrator',
            'about_me' => 'Test admin user for development purposes.',
            'top_rated' => 1,
        ]);
        $admin->assignRole($adminRole);

        // Create one Creator user
        $creator = User::create([
            'name' => 'Test',
            'last_name' => 'Creator',
            'email' => 'testcreator@test.com',
            'password' => Hash::make('password'),
            'phone' => '+1-555-0002',
            'status' => 1,
            'email_verified_at' => now(),
            'designation' => 'Test Contractor',
            'about_me' => 'Test creator user for development purposes.',
            'top_rated' => 0,
            'package_id' => $defaultPackage ? $defaultPackage->id : null,
        ]);
        $creator->assignRole($creatorRole);

        // Create one Viewer user
        $viewer = User::create([
            'name' => 'Test',
            'last_name' => 'Viewer',
            'email' => 'testviewer@test.com',
            'password' => Hash::make('password'),
            'phone' => '+1-555-0003',
            'status' => 1,
            'email_verified_at' => now(),
            'designation' => 'Test Client',
            'about_me' => 'Test viewer user for development purposes.',
            'top_rated' => 0,
            'package_id' => $defaultPackage ? $defaultPackage->id : null,
        ]);
        $viewer->assignRole($viewerRole);

        $this->command->info('Test users created successfully!');
        $this->command->info('Admin: testadmin@test.com / password');
        $this->command->info('Creator: testcreator@test.com / password');
        $this->command->info('Viewer: testviewer@test.com / password');
    }
}
