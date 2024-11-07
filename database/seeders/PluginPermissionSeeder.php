<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PluginPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create permission if it doesn't exist
        Permission::firstOrCreate(['name' => 'manage_plugins']);

        // Get existing admin role
        $adminRole = Role::where('name', 'admin')->first();

        if ($adminRole) {
            // Assign permission to admin role
            $adminRole->givePermissionTo('manage_plugins');

            // Get admin users
            $adminUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->get();

            // Assign permission to each admin user
            foreach ($adminUsers as $user) {
                $user->givePermissionTo('manage_plugins');
            }
        }
    }
}