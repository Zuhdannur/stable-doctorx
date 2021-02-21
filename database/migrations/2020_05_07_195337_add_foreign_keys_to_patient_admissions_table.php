<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPatientAdmissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('patient_admissions', function(Blueprint $table)
		{
			$table->foreign('admission_type_id', 'patient_admissions_ibfk_1')->references('id')->on('admission_types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('patient_id', 'patient_admissions_ibfk_2')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('status_id', 'patient_admissions_ibfk_4')->references('id')->on('appointment_status')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('patient_admissions', function(Blueprint $table)
		{
			$table->dropForeign('patient_admissions_ibfk_1');
			$table->dropForeign('patient_admissions_ibfk_2');
			$table->dropForeign('patient_admissions_ibfk_4');
		});
	}

}
