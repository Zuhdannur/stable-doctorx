<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientAdmissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('patient_admissions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('patient_id')->index('fk_patient_id');
			$table->string('admission_no', 100);
			$table->boolean('admission_type_id')->nullable()->index('fk_admission_type');
			$table->boolean('status_id')->index('fk_status_id');
			$table->string('notes', 194)->nullable();
			$table->string('reference_id', 191)->nullable();
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
		Schema::drop('patient_admissions');
	}

}
