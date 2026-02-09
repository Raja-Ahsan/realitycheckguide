<?php
  
namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Arr;
   
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if admin user already exists
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            $admin = User::create([
                'name' => 'Hardik',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin@123'),
                'status' => 1,
                'email_verified_at' => now(),
            ]);
            
            // Assign admin role if it exists
            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                $admin->assignRole($adminRole);
            }
            
            $this->command->info('Admin user created: admin@gmail.com / admin@123');
        } else {
            $this->command->info('Admin user already exists: admin@gmail.com');
        }
    }
}