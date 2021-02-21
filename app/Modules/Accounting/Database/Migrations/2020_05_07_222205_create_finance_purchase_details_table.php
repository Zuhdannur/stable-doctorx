<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinancePurchaseDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_purchase_details', function(Blueprint $table)
		{
			$table->bigIncrements('id', true);
			$table->bigInteger('purchase_id')->nullable()->unsigned();
			$table->string('items', 250)->nullable();
			$table->string('type', 100)->nullable();
			$table->integer('qty')->nullable();
			$table->bigInteger('price')->nullable();
			$table->bigInteger('price_total')->nullable();
			$table->string('desc', 250)->nullable();
			$table->string('tax_label', 100)->nullable();
			$table->integer('tax_value')->nullable();
			$table->timestamps();

			$table->foreign('purchase_id')
            ->references('id')->on('finance_purchases')
            ->onUpdate('cascade')
            ->onDelete('restrict');

		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('finance_purchase_details');
	}

}
