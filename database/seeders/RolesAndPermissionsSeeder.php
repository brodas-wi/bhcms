<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create permissions
        $permissions = [
            'view_users',
            'create_user',
            'edit_user',
            'delete_user',
            'assign_roles',
            'view_roles',
            'create_role',
            'edit_role',
            'delete_role',
            'view_permissions',
            'create_permission',
            'edit_permission',
            'delete_permission',
            'view_pages',
            'create_page',
            'edit_page',
            'delete_page',
            'publish_page',
            'view_articles',
            'create_article',
            'edit_article',
            'delete_article',
            'publish_article',
            'view_comments',
            'moderate_comments',
            'delete_comment',
            'view_settings',
            'edit_settings',
            'view_media',
            'upload_media',
            'delete_media',
            'view_templates',
            'create_template',
            'edit_template',
            'delete_template',
            'view_reports',
            'export_reports',
            'view_security_logs',
            'manage_security',
            'view_audit_logs',
        ];

        // Iterate permission data to insert permission queries
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and add permissions
        // Create role admin
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // Create role editor
        $role = Role::create(['name' => 'editor']);
        $role->givePermissionTo('view_articles');
        $role->givePermissionTo('create_article');
        $role->givePermissionTo('edit_article');
        $role->givePermissionTo('delete_article');
        $role->givePermissionTo('publish_article');
        $role->givePermissionTo('view_roles');
        $role->givePermissionTo('view_permissions');

        // Create role user
        $role = Role::create(['name' => 'user']);
    }
}
