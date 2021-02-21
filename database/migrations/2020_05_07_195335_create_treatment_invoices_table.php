<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTreatmentInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('treatment_invoices', function(Blueprint $table)
		{
			$table->integer('treatment_id')->primary();
			$table->integer('invoice_id')->nullable()->index('fk_inv_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('treatment_invoices');
	}

}
