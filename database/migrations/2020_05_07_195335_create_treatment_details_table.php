<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTreatmentDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('treatment_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('treatment_id')->index('fk_prescription_id');
			$table->string('name', 194);
			$table->string('description', 194)->nullable();
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
		Schema::drop('treatment_details');
	}

}
