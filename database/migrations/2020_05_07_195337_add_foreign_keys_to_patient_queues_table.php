<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPatientQueuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('patient_queues', function(Blueprint $table)
		{
			$table->foreign('appointment_type_id', 'fk_appointment_type_idx')->references('id')->on('admission_types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('patient_id', 'fk_patient_idx')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('patient_queues', function(Blueprint $table)
		{
			$table->dropForeign('fk_appointment_type_idx');
			$table->dropForeign('fk_patient_idx');
		});
	}

}
