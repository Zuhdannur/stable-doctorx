<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIncentiveStaffDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('incentive_staff_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('invoice_id')->index('fk_inv_idx');
			$table->integer('staff_id')->index('fk_staff_idx');
			$table->integer('incentive_id')->index('fk_incentive_idx');
			$table->enum('type', array('product','services'));
			$table->integer('entity_id');
			$table->enum('value_type', array('amount','percent'));
			$table->float('value', 10, 0);
			$table->float('price', 10, 0);
			$table->float('incenvite_value', 10, 0);
			$table->dateTime('date');
			$table->dateTime('created_at');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('incentive_staff_details');
	}

}
