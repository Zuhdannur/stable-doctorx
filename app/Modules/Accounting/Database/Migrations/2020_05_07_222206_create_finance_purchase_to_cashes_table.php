<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinancePurchaseToCashesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_purchase_to_cashes', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('transaction_id')->nullable(false)->unsigned();
			$table->bigInteger('purchase_id')->nullable(false)->unsigned();
			$table->timestamps();

			$table->foreign('purchase_id')
            ->references('id')->on('finance_purchases')
            ->onUpdate('cascade')
            ->onDelete('restrict');

            $table->foreign('transaction_id')
            ->references('id')->on('finance_transactions')
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
		Schema::drop('finance_purchase_to_cashes');
	}

}
