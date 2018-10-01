<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        DB::table('users')->insert([
            'name' => 'user',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'company_id' => $faker->randomElement(array(1,2,3,4,5)),
            'mobile_phone' => $faker->creditCardNumber,
            'home_address' => $faker->address,
            'home_city' => $faker->city,
            'home_state' => $faker->state,
            'home_postal_code' => $faker->postcode,
            'home_phone' => $faker->creditCardNumber,
            'id_no' => $faker->md5,
            'npwp_no' => $faker->md5,
            'pob' => $faker->city,
            'dob' => $faker->date,
            'employment_position' => $faker->jobTitle,
            'employment_salary' => $faker->numberBetween(5000000,25000000),
            'employment_duration' => $faker->numberBetween(1,60),
            'employment_status' => $faker->randomElement(array('kontrak','permanen')),
            'home_ownership' => $faker->randomElement(array('sendiri','keluarga','sewa','lain')),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'verified' => false,
            'active' => false,
            'bi_checking' => $faker->numberBetween(1,5)
        ]);
        for($i=1;$i<6;$i++) {
            DB::table('users')->insert([
                'name' => 'borrower'.$i,
                'email' => 'borrower'.$i.'@user.com',
                'password' => bcrypt('password'),
                'company_id' => $faker->randomElement(array(1,2,3,4,5)),
                'mobile_phone' => $faker->creditCardNumber,
                'home_address' => $faker->address,
                'home_city' => $faker->city,
                'home_state' => $faker->state,
                'home_postal_code' => $faker->postcode,
                'home_phone' => $faker->creditCardNumber,
                'id_no' => $faker->md5,
                'npwp_no' => $faker->md5,
                'pob' => $faker->city,
                'dob' => $faker->date,
                'employment_position' => $faker->jobTitle,
                'employment_salary' => $faker->numberBetween(5000000,25000000),
                'employment_duration' => $faker->numberBetween(1,60),
                'employment_status' => $faker->randomElement(array('kontrak','permanen')),
                'home_ownership' => $faker->randomElement(array('sendiri','keluarga','sewa','lain')),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'verified' => false,
                'active' => true,
                'bi_checking' => $faker->numberBetween(1,5)
            ]);
            DB::table('users')->insert([
                'name' => 'lender'.$i,
                'email' => 'lender'.$i.'@user.com',
                'password' => bcrypt('password'),
                'company_id' => $faker->randomElement(array(1,2,3,4,5)),
                'mobile_phone' => $faker->creditCardNumber,
                'home_address' => $faker->address,
                'home_city' => $faker->city,
                'home_state' => $faker->state,
                'home_postal_code' => $faker->postcode,
                'home_phone' => $faker->creditCardNumber,
                'id_no' => $faker->md5,
                'npwp_no' => $faker->md5,
                'pob' => $faker->city,
                'dob' => $faker->date,
                'employment_position' => $faker->jobTitle,
                'employment_salary' => $faker->numberBetween(5000000,25000000),
                'employment_duration' => $faker->numberBetween(1,60),
                'employment_status' => $faker->randomElement(array('kontrak','permanen')),
                'home_ownership' => $faker->randomElement(array('sendiri','keluarga','sewa','lain')),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
                'verified' => false,
                'active' => true,
                'bi_checking' => $faker->numberBetween(1,5)
            ]);
        }
    }
}
