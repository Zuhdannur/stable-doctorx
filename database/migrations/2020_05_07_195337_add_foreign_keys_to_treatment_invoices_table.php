<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTreatmentInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('treatment_invoices', function(Blueprint $table)
		{
			$table->foreign('treatment_id', 'treatment_invoices_ibfk_1')->references('id')->on('treatments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('invoice_id', 'treatment_invoices_ibfk_2')->references('id')->on('invoices')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('treatment_invoices', function(Blueprint $table)
		{
			$table->dropForeign('treatment_invoices_ibfk_1');
			$table->dropForeign('treatment_invoices_ibfk_2');
		});
	}

}
