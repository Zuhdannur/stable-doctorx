<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoices', function(Blueprint $table)
		{
			$table->foreign('patient_id', 'fk_inv_patient_idx')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('appointment_id', 'invoices_ibfk_1')->references('id')->on('appointments')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoices', function(Blueprint $table)
		{
			$table->dropForeign('fk_inv_patient_idx');
			$table->dropForeign('invoices_ibfk_1');
		});
	}

}
