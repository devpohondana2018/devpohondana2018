<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->double('amount_requested',12,2);
            $table->integer('loan_tenor_id')->unsigned();
            $table->text('description')->nullable();
            $table->date('date_expired');
            $table->double('provision_rate')->nullable();
            $table->double('provision_fee',12,2)->nullable();
            $table->double('interest_rate')->nullable();
            $table->double('interest_fee',12,2)->nullable();
            $table->double('invest_rate')->nullable();
            $table->double('invest_fee',12,2)->nullable();
            $table->double('amount_borrowed',12,2)->nullable();
            $table->double('amount_total',12,2)->nullable();
            $table->integer('loan_grade_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
