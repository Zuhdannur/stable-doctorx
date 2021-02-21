<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiagnoseItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('diagnose_items', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 64)->nullable();
			$table->string('name', 191);
			$table->string('description', 191)->nullable();
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
		Schema::drop('diagnose_items');
	}

}
