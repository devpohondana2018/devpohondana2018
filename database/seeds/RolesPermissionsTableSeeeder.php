<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesPermissionsTableSeeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $borrower = Role::create(['name' => 'borrower']);
        $lender = Role::create(['name' => 'lender']);
        for($i=1;$i<5;$i++) {
        	$borrower = User::where('name','borrower'.$i)->first()->assignRole('borrower');
        	$lender = User::where('name','lender'.$i)->first()->assignRole('lender');
        }
    }
}
