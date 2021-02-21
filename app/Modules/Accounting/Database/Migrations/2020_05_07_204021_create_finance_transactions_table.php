<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinanceTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_transactions', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('transaction_code', 250)->nullable()->unique('transaction_code');
			$table->smallInteger('trx_type_id')->unsigned();
			$table->string('attachment_file', 250)->nullable();
			$table->string('memo', 250)->nullable();
			$table->string('person', 45)->nullable();
			$table->integer('person_id')->nullable();
			$table->string('person_type', 100)->nullable();
			$table->smallInteger('potongan')->nullable();
			$table->date('trx_date')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();

			$table->foreign('trx_type_id')
            ->references('id')->on('finance_trx_types')
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
		Schema::drop('finance_transactions');
	}

}
