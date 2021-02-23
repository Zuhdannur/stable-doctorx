<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIncentiveStaffDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('incentive_staff_details', function(Blueprint $table)
		{
			$table->foreign('incentive_id', 'fk_incentive_idx')->references('id')->on('incentives')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('invoice_id', 'fk_inv_idx')->references('id')->on('invoices')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('staff_id', 'fk_staff_idx')->references('id')->on('staff')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('incentive_staff_details', function(Blueprint $table)
		{
			$table->dropForeign('fk_incentive_idx');
			$table->dropForeign('fk_inv_idx');
			$table->dropForeign('fk_staff_idx');
		});
	}

}
