<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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

        // Get default package for creators and viewers
        $defaultPackage = Package::first();

        // Create Admin Users
        $adminUsers = [
            [
                'name' => 'John',
                'last_name' => 'Administrator',
                'email' => 'admin@realitycheckguide.com',
                'password' => Hash::make('admin123'),
                'phone' => '+1-555-0101',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'System Administrator',
                'about_me' => 'Experienced system administrator with 10+ years in web development and platform management.',
                'top_rated' => 1,
            ],
            [
                'name' => 'Sarah',
                'last_name' => 'Manager',
                'email' => 'manager@realitycheckguide.com',
                'password' => Hash::make('manager123'),
                'phone' => '+1-555-0102',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Platform Manager',
                'about_me' => 'Platform manager focused on user experience and community growth.',
                'top_rated' => 1,
            ]
        ];

        foreach ($adminUsers as $adminData) {
            if (!User::where('email', $adminData['email'])->exists()) {
                $admin = User::create($adminData);
                $admin->assignRole($adminRole);
                $this->command->info('Created admin user: ' . $adminData['email']);
            } else {
                $this->command->info('Admin user already exists: ' . $adminData['email']);
            }
        }

        // Create Creator Users (Contractors/Service Providers)
        $creatorUsers = [
            [
                'name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@email.com',
                'password' => Hash::make('creator123'),
                'phone' => '+1-555-0201',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Licensed Electrician',
                'about_me' => 'Certified electrician with 15 years of experience in residential and commercial electrical work. Specializing in smart home installations and energy-efficient solutions.',
                'top_rated' => 1,
                'license' => 2024001,
                'package_id' => $defaultPackage ? $defaultPackage->id : null,
            ],
            [
                'name' => 'Lisa',
                'last_name' => 'Chen',
                'email' => 'lisa.chen@email.com',
                'password' => Hash::make('creator123'),
                'phone' => '+1-555-0202',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Interior Designer',
                'about_me' => 'Creative interior designer with expertise in modern and minimalist designs. Transforming spaces with innovative solutions and attention to detail.',
                'top_rated' => 1,
                'license' => 2024002,
                'package_id' => $defaultPackage ? $defaultPackage->id : null,
            ],
            [
                'name' => 'David',
                'last_name' => 'Martinez',
                'email' => 'david.martinez@email.com',
                'password' => Hash::make('creator123'),
                'phone' => '+1-555-0203',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Plumbing Specialist',
                'about_me' => 'Master plumber with 12 years of experience. Expert in emergency repairs, installations, and maintenance for both residential and commercial properties.',
                'top_rated' => 0,
                'license' => 2024003,
                'package_id' => $defaultPackage ? $defaultPackage->id : null,
            ],
            [
                'name' => 'Emma',
                'last_name' => 'Wilson',
                'email' => 'emma.wilson@email.com',
                'password' => Hash::make('creator123'),
                'phone' => '+1-555-0204',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Landscape Architect',
                'about_me' => 'Passionate landscape architect creating beautiful outdoor spaces. Specializing in sustainable landscaping and garden design.',
                'top_rated' => 0,
                'package_id' => $defaultPackage ? $defaultPackage->id : null,
            ]
        ];

        foreach ($creatorUsers as $creatorData) {
            if (!User::where('email', $creatorData['email'])->exists()) {
                $creator = User::create($creatorData);
                $creator->assignRole($creatorRole);
                $this->command->info('Created creator user: ' . $creatorData['email']);
            } else {
                $this->command->info('Creator user already exists: ' . $creatorData['email']);
            }
        }

        // Create Viewer Users (Clients/Job Posters)
        $viewerUsers = [
            [
                'name' => 'Robert',
                'last_name' => 'Thompson',
                'email' => 'robert.thompson@email.com',
                'password' => Hash::make('viewer123'),
                'phone' => '+1-555-0301',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Homeowner',
                'about_me' => 'Homeowner looking for reliable contractors for various home improvement projects.',
                'top_rated' => 0,
                'package_id' => $defaultPackage ? $defaultPackage->id : null,
            ],
            [
                'name' => 'Jennifer',
                'last_name' => 'Davis',
                'email' => 'jennifer.davis@email.com',
                'password' => Hash::make('viewer123'),
                'phone' => '+1-555-0302',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Business Owner',
                'about_me' => 'Small business owner seeking professional services for office renovations and maintenance.',
                'top_rated' => 0,
                'package_id' => $defaultPackage ? $defaultPackage->id : null,
            ],
            [
                'name' => 'Michael',
                'last_name' => 'Brown',
                'email' => 'michael.brown@email.com',
                'password' => Hash::make('viewer123'),
                'phone' => '+1-555-0303',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Property Manager',
                'about_me' => 'Property manager overseeing multiple residential properties, always looking for quality contractors.',
                'top_rated' => 0,
                'package_id' => $defaultPackage ? $defaultPackage->id : null,
            ],
            [
                'name' => 'Amanda',
                'last_name' => 'Garcia',
                'email' => 'amanda.garcia@email.com',
                'password' => Hash::make('viewer123'),
                'phone' => '+1-555-0304',
                'status' => 1,
                'email_verified_at' => now(),
                'designation' => 'Real Estate Developer',
                'about_me' => 'Real estate developer working on multiple projects, need reliable contractors for various construction phases.',
                'top_rated' => 0,
                'package_id' => $defaultPackage ? $defaultPackage->id : null,
            ]
        ];

        foreach ($viewerUsers as $viewerData) {
            if (!User::where('email', $viewerData['email'])->exists()) {
                $viewer = User::create($viewerData);
                $viewer->assignRole($viewerRole);
                $this->command->info('Created viewer user: ' . $viewerData['email']);
            } else {
                $this->command->info('Viewer user already exists: ' . $viewerData['email']);
            }
        }

        $this->command->info('User seeder completed successfully!');
    }
}
