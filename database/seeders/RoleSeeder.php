<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $creatorRole = Role::firstOrCreate(['name' => 'Creator', 'guard_name' => 'web']);
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);

        // Create permissions
        $permissions = [
            // Admin permissions (all permissions)
            'admin-dashboard',
            'manage-users',
            'manage-roles',
            'manage-permissions',
            'manage-jobposts',
            'manage-bids',
            'manage-categories',
            'manage-settings',
            
                    // Creator permissions
        'view-jobs',
        'bid-on-jobs',
        'manage-bids',
        'contact-users',
        'creator-dashboard',
        
        // Viewer permissions
        'create-jobpost',
        'manage-own-jobposts',
        'view-own-bids',
        'viewer-dashboard',
            // Submittals
            'submittals-list',
            'submittals-create',
            'submittals-edit',
            'submittals-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());
        
        $creatorRole->givePermissionTo([
            'view-jobs',
            'bid-on-jobs',
            'manage-bids',
            'contact-users',
            'creator-dashboard',
            'submittals-list',
            'submittals-create',
            'submittals-edit',
        ]);
        
        $viewerRole->givePermissionTo([
            'create-jobpost',
            'manage-own-jobposts',
            'view-own-bids',
            'viewer-dashboard',
            'submittals-list',
            'submittals-create',
            'submittals-edit',
        ]);
    }
}
