<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinanceBiayaTrxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_biaya_trxes', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('transaction_id')->unsigned();
			$table->boolean('status')->nullable()->default(1);
			$table->dateTime('due_date')->nullable();
			$table->integer('remain_payment')->nullable()->default(0);
			$table->integer('total')->nullable()->default(0);
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();

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
		Schema::drop('finance_biaya_trxes');
	}

}
