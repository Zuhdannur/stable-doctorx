<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAppointmentInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('appointment_invoices', function(Blueprint $table)
		{
			$table->foreign('appointment_id', 'fk_app_id')->references('id')->on('appointments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('invoice_id', 'fk_inv_id')->references('id')->on('invoices')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('appointment_invoices', function(Blueprint $table)
		{
			$table->dropForeign('fk_app_id');
			$table->dropForeign('fk_inv_id');
		});
	}

}
