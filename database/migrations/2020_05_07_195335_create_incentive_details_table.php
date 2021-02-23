<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIncentiveDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('incentive_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('incentive_id')->index('incentive_ids');
			$table->enum('type', array('product','services'));
			$table->integer('entity_id');
			$table->enum('value_type', array('amount','percent'));
			$table->float('value', 10, 0);
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
		Schema::drop('incentive_details');
	}

}
