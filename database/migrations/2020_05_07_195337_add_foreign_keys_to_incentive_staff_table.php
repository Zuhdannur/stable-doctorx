<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIncentiveStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('incentive_staff', function(Blueprint $table)
		{
			$table->foreign('incentive_id', 'incentive_id')->references('id')->on('incentives')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('staff_id', 'staff_id')->references('id')->on('staff')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('incentive_staff', function(Blueprint $table)
		{
			$table->dropForeign('incentive_id');
			$table->dropForeign('staff_id');
		});
	}

}
