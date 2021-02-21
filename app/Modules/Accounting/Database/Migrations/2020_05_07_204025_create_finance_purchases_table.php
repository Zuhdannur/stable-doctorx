<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinancePurchasesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_purchases', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('transaction_id')->nullable(false)->unsigned();
			$table->boolean('status')->nullable()->default(0);
			$table->date('due_date')->nullable();
			$table->bigInteger('remain_payment')->nullable();
			$table->bigInteger('total')->nullable();
			$table->timestamps();

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
		Schema::drop('finance_purchases');
	}

}
