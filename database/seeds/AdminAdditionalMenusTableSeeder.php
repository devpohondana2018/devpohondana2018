<?php

use Illuminate\Database\Seeder;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Menu;

class AdminAdditionalMenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// User submenus
    	Menu::insert([
            [
                'parent_id' => 2,
                'order'     => 1,
                'title'     => 'Unverified',
                'icon'      => 'fa-users',
                'uri'       => 'users?&verified=0',
            ],
            [
                'parent_id' => 2,
                'order'     => 2,
                'title'     => 'Verified',
                'icon'      => 'fa-users',
                'uri'       => 'users?&verified=1',
            ]
        ]);

        Menu::where('title','Unverified')->first()->roles()->save(Role::where('slug','access_users')->first());
        Menu::where('title','Verified')->first()->roles()->save(Role::where('slug','access_users')->first());

    	// Loan Submenus
        $loan_submenus = [
            [
                'parent_id' => 3,
                'order'     => 1,
                'title'     => 'Pending',
                'icon'      => 'fa-list-alt',
                'uri'       => 'loans?&status_id=1',
            ],
            [
                'parent_id' => 3,
                'order'     => 2,
                'title'     => 'Approved (by admin)',
                'icon'      => 'fa-list-alt',
                'uri'       => 'loans?&status_id=2',
            ],
            [
                'parent_id' => 3,
                'order'     => 3,
                'title'     => 'Declined (by admin)',
                'icon'      => 'fa-list-alt',
                'uri'       => 'loans?&status_id=4',
            ],
            [
                'parent_id' => 3,
                'order'     => 4,
                'title'     => 'Accepted (by user)',
                'icon'      => 'fa-list-alt',
                'uri'       => 'loans?&status_id=3',
            ],
            [
                'parent_id' => 3,
                'order'     => 5,
                'title'     => 'Rejected (by user)',
                'icon'      => 'fa-list-alt',
                'uri'       => 'loans?&status_id=9',
            ],
            [
                'parent_id' => 3,
                'order'     => 6,
                'title'     => 'Completed',
                'icon'      => 'fa-list-alt',
                'uri'       => 'loans?&status_id=7',
            ]
        ];

        foreach ($loan_submenus as $loan_submenu) {
            $menu = Menu::create($loan_submenu);
            $menu->roles()->save(Role::where('slug','access_loans')->first()); //loans
        }

		// Investment Submenus
        $investment_submenus = [
            [
                'parent_id' => 4,
                'order'     => 1,
                'title'     => 'Pending',
                'icon'      => 'fa-list-alt',
                'uri'       => 'investments?&status_id=1',
            ],
            [
                'parent_id' => 4,
                'order'     => 2,
                'title'     => 'Approved (by admin)',
                'icon'      => 'fa-list-alt',
                'uri'       => 'investments?&status_id=2',
            ],
            [
                'parent_id' => 4,
                'order'     => 3,
                'title'     => 'Declined (by admin)',
                'icon'      => 'fa-list-alt',
                'uri'       => 'investments?&status_id=4',
            ],
            [
                'parent_id' => 4,
                'order'     => 4,
                'title'     => 'Accepted (by user)',
                'icon'      => 'fa-list-alt',
                'uri'       => 'investments?&status_id=3',
            ],
            [
                'parent_id' => 4,
                'order'     => 5,
                'title'     => 'Rejected (by user)',
                'icon'      => 'fa-list-alt',
                'uri'       => 'investments?&status_id=9',
            ],
            [
                'parent_id' => 4,
                'order'     => 6,
                'title'     => 'Completed',
                'icon'      => 'fa-list-alt',
                'uri'       => 'investments?&status_id=7',
            ]
        ];

        foreach ($investment_submenus as $investment_submenu) {
            $menu = Menu::create($investment_submenu);
            $menu->roles()->save(Role::where('slug','access_investments')->first());
        }

		// Installments Submenus
        $loan_installment_menu = Menu::create([
            'parent_id' => 5,
            'order'     => 1,
            'title'     => 'Loan Installments',
            'icon'      => 'fa-download',
            'uri'       => 'installments?&installmentable_type=App%5CLoan',
        ]);
        $loan_installment_menu->roles()->save(Role::where('slug','access_loans')->first());

        $investment_installment_menu = Menu::create([
            'parent_id' => 5,
            'order'     => 2,
            'title'     => 'Investment Installments',
            'icon'      => 'fa-download',
            'uri'       => 'installments?&installmentable_type=App%5CInvestment',
        ]);
        $investment_installment_menu->roles()->save(Role::where('slug','access_investments')->first());
    }
}
