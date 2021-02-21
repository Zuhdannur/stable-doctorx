<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoiceServiceDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoice_service_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('invoice_id')->index('fk_invoice_id');
			$table->integer('product_id')->index('fk_product_id');
			$table->float('price', 10, 0);
			$table->boolean('qty');
			$table->string('type', 64);
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
		Schema::drop('invoice_service_details');
	}

}
