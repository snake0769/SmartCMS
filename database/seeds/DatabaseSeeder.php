<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(SystemConfigsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(RequestUrlsTableSeeder::class);

        /*// 创建权限
        $permission_edit = new Permission();
        $permission_edit->name = 'edit-post';
        $permission_edit->label = 'Can edit post';
        $permission_edit->save();
        $permission_delete = new Permission;
        $permission_delete->name = 'delete-post';
        $permission_delete->label = 'Can delete post';
        $permission_delete->save();

        // 创建角色
        $role_editor = new Role();
        $role_editor->name = 'editor';
        $role_editor->label = 'The editor of the site';
        $role_editor->save();
        $role_editor->givePermissionTo($permission_edit);
        $role_admin = new Role();
        $role_admin->name = 'admin';
        $role_admin->label = 'The admin of the site';
        $role_admin->save();

        // 给角色分配权限
        $role_admin->givePermissionTo($permission_edit);
        $role_admin->givePermissionTo($permission_delete);

        // 创建用户
        $editor = factory(User::class)->create();

        // 给用户分配角色
        $editor->assignRole($role_editor->name);
        $admin = factory(User::class)->create();
        $admin->assignRole($role_admin->name);*/
    }
}
