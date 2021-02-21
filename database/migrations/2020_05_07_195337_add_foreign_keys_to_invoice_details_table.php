<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInvoiceDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoice_details', function(Blueprint $table)
		{
			$table->foreign('invoice_id', 'fk_invoice_id')->references('id')->on('invoices')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoice_details', function(Blueprint $table)
		{
			$table->dropForeign('fk_invoice_id');
		});
	}

}
