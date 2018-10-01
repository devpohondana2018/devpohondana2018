<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rank');
            $table->double('max_loan_limit');
            $table->double('platform_rate');
            $table->double('borrower_rate');
            $table->double('lender_rate');
            $table->boolean('active')->default(false);
            $table->integer('loan_tenor_id')->unsigned();
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
        Schema::dropIfExists('loan_grades');
    }
}
