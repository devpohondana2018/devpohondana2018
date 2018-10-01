<?php

use Illuminate\Database\Seeder;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Menu;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create a user.
        Administrator::create([
            'username' => 'super',
            'password' => bcrypt('super'),
            'name'     => 'Super Administrator',
        ]);

        // create a role.
        Role::create([
            'name' => 'Super Administrator',
            'slug' => 'super_administrator',
        ]);

        // Add all permissions to tole
        foreach (Permission::all() as $permission) {
            Role::where('slug','super_administrator')->first()->permissions()->save($permission);
        }
        
        // Add all role to user
        foreach (Role::where('slug','<>','auditor')->get() as $role) {
            Administrator::where('username','super')->first()->roles()->save($role);
        }

        // add all menus to super role
        // foreach (Menu::all() as $menu) {
        //     $menu->roles()->save(Role::where('slug','super_administrator')->first());
        // }
    }
}
