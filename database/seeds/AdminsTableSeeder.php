<?php

use Illuminate\Database\Seeder;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Menu;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create users
        Administrator::truncate();
        Administrator::create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'name'     => 'Administrator',
        ]);
        Administrator::create([
            'username' => 'user_reviewer',
            'password' => bcrypt('password'),
            'name'     => 'User Reviewer Admin',
        ]);
        Administrator::create([
            'username' => 'loan_reviewer',
            'password' => bcrypt('password'),
            'name'     => 'Loan Reviewer Admin',
        ]);
        Administrator::create([
            'username' => 'loan_approver',
            'password' => bcrypt('password'),
            'name'     => 'Loan Approver Admin',
        ]);
        Administrator::create([
            'username' => 'investment_reviewer',
            'password' => bcrypt('password'),
            'name'     => 'Investment Reviewer Admin',
        ]);
        Administrator::create([
            'username' => 'investment_approver',
            'password' => bcrypt('password'),
            'name'     => 'Investment Approver Admin',
        ]);
        Administrator::create([
            'username' => 'transaction_reviewer',
            'password' => bcrypt('password'),
            'name'     => 'Transaction Reviewer Admin',
        ]);
        Administrator::create([
            'username' => 'transaction_approver',
            'password' => bcrypt('password'),
            'name'     => 'Transaction Approver Admin',
        ]);
        Administrator::create([
            'username' => 'system_admin',
            'password' => bcrypt('password'),
            'name'     => 'System Administrator',
        ]);
        Administrator::create([
            'username' => 'website_admin',
            'password' => bcrypt('password'),
            'name'     => 'Website Administrator',
        ]);
        Administrator::create([
            'username' => 'auditor',
            'password' => bcrypt('password'),
            'name'     => 'Auditor',
        ]);

        // create roles
        Role::truncate();
        Role::create([
            'name' => 'Administrator',
            'slug' => 'administrator',
        ]);
        Role::create([
            'name' => 'Access users',
            'slug' => 'access_users'
        ]);
        Role::create([
            'name' => 'User Reviewer',
            'slug' => 'user_reviewer'
        ]);
        Role::create([
            'name' => 'Access Loans',
            'slug' => 'access_loans'
        ]);
        Role::create([
            'name' => 'Loan Reviewer',
            'slug' => 'loan_reviewer'
        ]);
        Role::create([
            'name' => 'Loan Approver',
            'slug' => 'loan_approver'
        ]);
        Role::create([
            'name' => 'Access Investments',
            'slug' => 'access_investments'
        ]);
        Role::create([
            'name' => 'Investment Reviewer',
            'slug' => 'investment_reviewer'
        ]);
        Role::create([
            'name' => 'Investment Approver',
            'slug' => 'investment_approver'
        ]);
        Role::create([
            'name' => 'Access Transactions',
            'slug' => 'access_transactions'
        ]);
        Role::create([
            'name' => 'Transaction Reviewer',
            'slug' => 'transaction_reviewer'
        ]);
        Role::create([
            'name' => 'Transaction Approver',
            'slug' => 'transaction_approver'
        ]);
        Role::create([
            'name' => 'System Admin',
            'slug' => 'system_admin'
        ]);
        Role::create([
            'name' => 'Website Admin',
            'slug' => 'website_admin'
        ]);
        Role::create([
            'name' => 'Auditor',
            'slug' => 'auditor'
        ]);

        // add role to user.
        Administrator::where('username','admin')->first()->roles()->save(Role::where('slug','administrator')->first());
        
        Administrator::where('username','user_reviewer')->first()->roles()->save(Role::where('slug','access_users')->first());
        Administrator::where('username','user_reviewer')->first()->roles()->save(Role::where('slug','user_reviewer')->first());
        
        Administrator::where('username','loan_reviewer')->first()->roles()->save(Role::where('slug','access_loans')->first());
        Administrator::where('username','loan_reviewer')->first()->roles()->save(Role::where('slug','loan_reviewer')->first());
        Administrator::where('username','loan_approver')->first()->roles()->save(Role::where('slug','access_loans')->first());
        Administrator::where('username','loan_approver')->first()->roles()->save(Role::where('slug','loan_approver')->first());
        
        Administrator::where('username','investment_reviewer')->first()->roles()->save(Role::where('slug','access_investments')->first());
        Administrator::where('username','investment_reviewer')->first()->roles()->save(Role::where('slug','investment_reviewer')->first());
        Administrator::where('username','investment_approver')->first()->roles()->save(Role::where('slug','access_investments')->first());
        Administrator::where('username','investment_approver')->first()->roles()->save(Role::where('slug','investment_approver')->first());
        
        Administrator::where('username','transaction_reviewer')->first()->roles()->save(Role::where('slug','access_transactions')->first());
        Administrator::where('username','transaction_reviewer')->first()->roles()->save(Role::where('slug','transaction_reviewer')->first());
        Administrator::where('username','transaction_approver')->first()->roles()->save(Role::where('slug','access_transactions')->first());
        Administrator::where('username','transaction_approver')->first()->roles()->save(Role::where('slug','transaction_approver')->first());
        
        Administrator::where('username','system_admin')->first()->roles()->save(Role::where('slug','system_admin')->first());
        Administrator::where('username','website_admin')->first()->roles()->save(Role::where('slug','website_admin')->first());

        Administrator::where('username','auditor')->first()->roles()->save(Role::where('slug','access_users')->first());
        Administrator::where('username','auditor')->first()->roles()->save(Role::where('slug','access_loans')->first());
        Administrator::where('username','auditor')->first()->roles()->save(Role::where('slug','access_investments')->first());
        Administrator::where('username','auditor')->first()->roles()->save(Role::where('slug','access_transactions')->first());
        Administrator::where('username','auditor')->first()->roles()->save(Role::where('slug','auditor')->first());

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'All permission',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Dashboard',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Login',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'User setting',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Auth management',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ],
            [
                'name'        => 'Access users',
                'slug'        => 'user.access',
                'http_method' => '',
                'http_path'   => "/users*",
            ],
            [
                'name'        => 'Access loans',
                'slug'        => 'loan.access',
                'http_method' => '',
                'http_path'   => "/loans*",
            ],
            [
                'name'        => 'Access investments',
                'slug'        => 'investment.access',
                'http_method' => '',
                'http_path'   => "/investments*",
            ],
            [
                'name'        => 'Access installments',
                'slug'        => 'installment.access',
                'http_method' => '',
                'http_path'   => "/installments*",
            ],
            [
                'name'        => 'Access transactions',
                'slug'        => 'transaction.access',
                'http_method' => '',
                'http_path'   => "/transactions*",
            ],
            [
                'name'        => 'Access companies',
                'slug'        => 'company.access',
                'http_method' => '',
                'http_path'   => "/companies*",
            ],
            [
                'name'        => 'Access banks',
                'slug'        => 'bank.access',
                'http_method' => '',
                'http_path'   => "/banks*",
            ],
            [
                'name'        => 'Access bank accounts',
                'slug'        => 'bank_account.access',
                'http_method' => '',
                'http_path'   => "/bank_accounts*",
            ],
            [
                'name'        => 'Access loan grades',
                'slug'        => 'grade.access',
                'http_method' => '',
                'http_path'   => "/grades*",
            ],
            [
                'name'        => 'Access loan tenors',
                'slug'        => 'tenor.access',
                'http_method' => '',
                'http_path'   => "/loan_tenors*",
            ],
            [
                'name'        => 'Verify users',
                'slug'        => 'user.verifies',
                'http_method' => '',
                'http_path'   => "/users*",
            ],
            [
                'name'        => 'Decline loans',
                'slug'        => 'loan.declines',
                'http_method' => '',
                'http_path'   => "/loans*",
            ],
            [
                'name'        => 'Approve loans',
                'slug'        => 'loan.approves',
                'http_method' => '',
                'http_path'   => "/loans*",
            ],
            [
                'name'        => 'Verify loans',
                'slug'        => 'loan.verifies',
                'http_method' => '',
                'http_path'   => "/loans*",
            ],
            [
                'name'        => 'Approve investments',
                'slug'        => 'investment.approves',
                'http_method' => '',
                'http_path'   => "/investments*",
            ],
            [
                'name'        => 'Decline investments',
                'slug'        => 'investment.declines',
                'http_method' => '',
                'http_path'   => "/investments*",
            ],
            [
                'name'        => 'Verify investments',
                'slug'        => 'investment.verifies',
                'http_method' => '',
                'http_path'   => "/investments*",
            ],
            [
                'name'        => 'Access system settings',
                'slug'        => 'system.settings',
                'http_method' => '',
                'http_path'   => "/config*",
            ],
            [
                'name'        => 'Access website settings',
                'slug'        => 'website.settings',
                'http_method' => '',
                'http_path'   => '',
            ],
            [
                'name'        => 'Verify transactions',
                'slug'        => 'transaction.verifies',
                'http_method' => '',
                'http_path'   => "/transactions*",
            ],
            [
                'name'        => 'Decline transactions',
                'slug'        => 'transaction.declines',
                'http_method' => '',
                'http_path'   => "/transactions*",
            ],
            [
                'name'        => 'Approve transactions',
                'slug'        => 'transaction.approves',
                'http_method' => '',
                'http_path'   => "/transactions*",
            ],
            [
                'name'        => 'Manage admin users',
                'slug'        => 'auth.admin_users',
                'http_method' => '',
                'http_path'   => "/auth/users*",
            ],
            [
                'name'        => 'Update users',
                'slug'        => 'user.update',
                'http_method' => '',
                'http_path'   => "/users*",
            ]
        ]);

        // Add general permissions
        $general_permissions = array('Dashboard','Login','User setting');
        foreach (Role::all() as $role) {
            foreach ($general_permissions as $key => $value) {
                $role->permissions()->save(Permission::where('name',$value)->first());
            }
        }

        // Add spesific permissions to custom role
        $user_access_permissions = array('Access users');
        foreach ($user_access_permissions as $key => $value) {
            Role::where('slug','access_users')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $user_permissions = array('Access users','Verify users');
        foreach ($user_permissions as $key => $value) {
            Role::where('slug','user_reviewer')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        foreach (Permission::where('name','<>','Manage admin users')->get() as $permission) {
            Role::where('slug','administrator')->first()->permissions()->save($permission);
        }

        $loan_access_permissions = array('Access loans','Access installments');
        foreach ($loan_access_permissions as $key => $value) {
            Role::where('slug','access_loans')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $loan_reviewer_permissions = array('Access loans','Access installments','Verify loans','Decline loans');
        foreach ($loan_reviewer_permissions as $key => $value) {
            Role::where('slug','loan_reviewer')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $loan_approver_permissions = array('Access loans','Access installments','Verify loans','Decline loans','Approve loans');
        foreach ($loan_approver_permissions as $key => $value) {
            Role::where('slug','loan_approver')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $investment_access_permissions = array('Access investments','Access installments');
        foreach ($investment_access_permissions as $key => $value) {
            Role::where('slug','access_investments')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $investment_reviewer_permissions = array('Access investments','Access installments','Verify investments','Decline investments');
        foreach ($investment_reviewer_permissions as $key => $value) {
            Role::where('slug','investment_reviewer')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $investment_approver_permissions = array('Access investments','Access installments','Verify investments','Decline investments','Approve investments');
        foreach ($investment_approver_permissions as $key => $value) {
            Role::where('slug','investment_approver')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $transaction_access_permissions = array('Access transactions');
        foreach ($transaction_access_permissions as $key => $value) {
            Role::where('slug','access_transactions')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $transaction_reviewer_permissions = array('Access loans','Access installments','Access transactions','Verify transactions','Decline transactions');
        foreach ($transaction_reviewer_permissions as $key => $value) {
            Role::where('slug','transaction_reviewer')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $transaction_approver_permissions = array('Access loans','Access installments','Access transactions','Verify transactions','Decline transactions','Approve transactions');
        foreach ($transaction_approver_permissions as $key => $value) {
            Role::where('slug','transaction_approver')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $system_permissions = array('Access system settings','Manage admin users');
        foreach ($system_permissions as $key => $value) {
            Role::where('slug','system_admin')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        $website_permissions = array('Access website settings');
        foreach ($website_permissions as $key => $value) {
            Role::where('slug','website_admin')->first()->permissions()->save(Permission::where('name',$value)->first());
        }

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'Index',
                'icon'      => 'fa-dashboard',
                'uri'       => '/',
            ],
            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => 'Users',
                'icon'      => 'fa-users',
                'uri'       => 'users',
            ],
            [
                'parent_id' => 0,
                'order'     => 3,
                'title'     => 'Loans',
                'icon'      => 'fa-list-alt',
                'uri'       => 'loans',
            ],
            [
                'parent_id' => 0,
                'order'     => 4,
                'title'     => 'Investments',
                'icon'      => 'fa-money',
                'uri'       => 'investments',
            ],
            [
                'parent_id' => 0,
                'order'     => 5,
                'title'     => 'Installments',
                'icon'      => 'fa-download',
                'uri'       => 'installments',
            ],
            [
                'parent_id' => 0,
                'order'     => 6,
                'title'     => 'Transactions',
                'icon'      => 'fa-exchange',
                'uri'       => 'transactions',
            ],
            [
                'parent_id' => 0,
                'order'     => 7,
                'title'     => 'Companies',
                'icon'      => 'fa-building',
                'uri'       => 'companies',
            ],
            [
                'parent_id' => 0,
                'order'     => 8,
                'title'     => 'Banks',
                'icon'      => 'fa-university',
                'uri'       => 'banks',
            ],
            [
                'parent_id' => 0,
                'order'     => 9,
                'title'     => 'Bank Accounts',
                'icon'      => 'fa-book',
                'uri'       => 'bank_accounts',
            ],
            [
                'parent_id' => 0,
                'order'     => 10,
                'title'     => 'Loan Grades',
                'icon'      => 'fa-calculator',
                'uri'       => 'grades',
            ],
            [
                'parent_id' => 0,
                'order'     => 11,
                'title'     => 'Loan Tenors',
                'icon'      => 'fa-calendar',
                'uri'       => 'loan_tenors',
            ],
            [
                'parent_id' => 0,
                'order'     => 12,
                'title'     => 'Admin',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'parent_id' => 12,
                'order'     => 13,
                'title'     => 'Users',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 12,
                'order'     => 14,
                'title'     => 'Roles',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'parent_id' => 12,
                'order'     => 15,
                'title'     => 'Permission',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
            ],
            [
                'parent_id' => 12,
                'order'     => 16,
                'title'     => 'Menu',
                'icon'      => 'fa-bars',
                'uri'       => 'auth/menu',
            ],
            [
                'parent_id' => 12,
                'order'     => 17,
                'title'     => 'Operation log',
                'icon'      => 'fa-history',
                'uri'       => 'auth/logs',
            ],
            [
                'parent_id' => 12,
                'order'     => 18,
                'title'     => 'Media manager',
                'icon'      => 'fa-file',
                'uri'       => 'media',
            ],
            [
                'parent_id' => 12,
                'order'     => 19,
                'title'     => 'Config',
                'icon'      => 'fa-toggle-on',
                'uri'       => 'config',
            ],
            [
                'parent_id' => 12,
                'order'     => 20,
                'title'     => 'Backup',
                'icon'      => 'fa-copy',
                'uri'       => 'backup',
            ],
            [
                'parent_id' => 12,
                'order'     => 21,
                'title'     => 'Scheduling',
                'icon'      => 'fa-clock-o',
                'uri'       => 'scheduling',
            ]
            
        ]);

        DB::table('admin_config')->insert([
            'name' => 'max_loan_limit',
            'value' => 2000000000,
        ]);
        DB::table('admin_config')->insert([
            'name' => 'email_admin',
            'value' => 'admin@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'email_system',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'user_unverified_email_notification',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'user_verified_email_notification',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'loan_approved_email_notification',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'loan_accepted_email_notification',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'loan_declined_email_notification',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'investment_approved_email_notification',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'investment_accepted_email_notification',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'investment_declined_email_notification',
            'value' => 'notification@pohondana.id',
        ]);
        DB::table('admin_config')->insert([
            'name' => 'installments_email_reminder_due_days',
            'value' => '20',
        ]);

        // Add roles to menu
        Menu::find(2)->roles()->save(Role::find(2)); //user
        Menu::find(3)->roles()->save(Role::find(4)); //loan
        Menu::find(4)->roles()->save(Role::find(7)); //investment
        Menu::where('title','Installments')->first()->roles()->save(Role::where('slug','access_investments')->first());
        Menu::where('title','Installments')->first()->roles()->save(Role::where('slug','access_loans')->first());
        Menu::find(6)->roles()->save(Role::find(10)); //transaction
        Menu::where('title','Companies')->first()->roles()->save(Role::where('slug','system_admin')->first());
        Menu::where('title','Banks')->first()->roles()->save(Role::where('slug','system_admin')->first());
        Menu::where('title','Bank Accounts')->first()->roles()->save(Role::where('slug','system_admin')->first());
        Menu::where('title','Loan Grades')->first()->roles()->save(Role::where('slug','system_admin')->first());
        Menu::where('title','Loan Tenors')->first()->roles()->save(Role::where('slug','system_admin')->first());
        Menu::where('title','Admin')->first()->roles()->save(Role::where('slug','system_admin')->first());

        // add all menus to super role
        // foreach (Menu::all() as $menu) {
        //     $menu->roles()->save(Role::where('slug','super_administrator')->first());
        // }
    }
}
