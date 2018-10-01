<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('home_address')->nullable();
            $table->string('home_city')->nullable();
            $table->string('home_state')->nullable();
            $table->string('home_postal_code')->nullable();
            $table->string('home_ownership')->nullable();
            $table->string('home_doc')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('id_no')->nullable();
            $table->string('id_doc')->nullable();
            $table->string('npwp_no')->nullable();
            $table->string('pob')->nullable();
            $table->string('dob')->nullable();
            $table->string('company_id')->nullable();
            $table->double('employment_salary',12,2)->nullable();
            $table->string('employment_salary_slip')->nullable();
            $table->string('employment_position')->nullable(); 
            $table->string('employment_duration')->nullable();
            $table->string('employment_status')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
