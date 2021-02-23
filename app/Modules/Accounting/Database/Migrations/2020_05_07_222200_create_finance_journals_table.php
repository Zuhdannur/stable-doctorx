<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFinanceJournalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('finance_journals', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->bigInteger('transaction_id')->nullable(false)->index('transaction_id')->unsigned();
			$table->smallInteger('account_id')->nullable(false)->unsigned();
			$table->bigInteger('balance')->nullable();
			$table->enum('type', array('1','2'))->nullable();
			$table->bigInteger('value')->nullable();
			$table->string('tax', 250);
			$table->string('description', 250)->nullable();
			$table->string('tags', 150)->nullable();
			$table->timestamps();

			$table->foreign('account_id')
            ->references('id')->on('finance_accounts')
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
		Schema::drop('finance_journals');
	}

}
