<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiagnosesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('diagnoses', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('appointment_id')->index('fk_appointment_idx');
			$table->integer('diagnose_item_id')->nullable()->index('diagnose_item_id');
			$table->string('name', 194);
			$table->string('instruction', 194)->nullable();
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
		Schema::drop('diagnoses');
	}

}
