<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// create table for payments history
        Schema::create('payments_history', function(Blueprint $table)
        {
            $table->increments('ph_id')->unsigned();
            $table->string('ph_payment_system', 64);
            $table->string('ph_payment_id', 32);
            $table->dateTime('ph_date');
            $table->decimal('ph_amount', 9,2);
            $table->string('ph_currency', 3);
            $table->string('ph_customer_name', 128);
            $table->string('ph_cc_number', 19);
            $table->index('ph_payment_system');
            $table->index('ph_payment_id');
            $table->index('ph_date');
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//remove table
        Schema::drop('payments_history');
	}

}
