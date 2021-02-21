<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIncentiveDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('incentive_details', function(Blueprint $table)
		{
			$table->foreign('incentive_id', 'incentive_ids')->references('id')->on('incentives')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('incentive_details', function(Blueprint $table)
		{
			$table->dropForeign('incentive_ids');
		});
	}

}
