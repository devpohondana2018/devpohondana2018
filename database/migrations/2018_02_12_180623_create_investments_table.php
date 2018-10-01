<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->increments('id');
            $table->double('amount_invested',12,2);
            $table->double('invest_rate')->nullable();
            $table->double('invest_fee',12,2)->nullable();
            $table->double('amount_total',12,2)->nullable();
            $table->integer('loan_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('status_id')->unsigned()->default(1);
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
        Schema::dropIfExists('investments');
    }
}
