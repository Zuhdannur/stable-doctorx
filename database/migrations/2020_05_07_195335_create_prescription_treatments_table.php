<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePrescriptionTreatmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prescription_treatments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('prescription_id')->index('fk_prescription_id');
			$table->integer('services_id')->nullable()->index('services_id');
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
		Schema::drop('prescription_treatments');
	}

}
