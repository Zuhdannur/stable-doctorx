<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIncentiveStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('incentive_staff', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('staff_id')->index('staff_id');
			$table->integer('incentive_id')->index('incentive_id');
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
		Schema::drop('incentive_staff');
	}

}
