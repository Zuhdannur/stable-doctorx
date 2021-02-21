<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinanceTaxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_taxes', function(Blueprint $table)
		{
			$table->smallIncrements('id');
			$table->string('tax_name', 100)->nullable();
			$table->integer('account_tax_sales')->nullable();
			$table->integer('account_tax_purchase')->nullable();
			$table->smallInteger('percentage')->nullable();
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('finance_taxes');
	}

}
