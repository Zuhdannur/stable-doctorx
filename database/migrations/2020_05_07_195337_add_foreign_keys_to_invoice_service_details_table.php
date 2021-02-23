<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInvoiceServiceDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoice_service_details', function(Blueprint $table)
		{
			$table->foreign('invoice_id', 'invoice_service_details_ibfk_1')->references('id')->on('invoices')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('product_id', 'invoice_service_details_ibfk_2')->references('id')->on('products')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoice_service_details', function(Blueprint $table)
		{
			$table->dropForeign('invoice_service_details_ibfk_1');
			$table->dropForeign('invoice_service_details_ibfk_2');
		});
	}

}
