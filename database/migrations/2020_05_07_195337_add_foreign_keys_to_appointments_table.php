<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAppointmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('appointments', function(Blueprint $table)
		{
			$table->foreign('patient_id', 'fk_patient_id')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('staff_id', 'fk_staff_id')->references('id')->on('staff')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('status_id', 'fk_status_id')->references('id')->on('appointment_status')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('appointments', function(Blueprint $table)
		{
			$table->dropForeign('fk_patient_id');
			$table->dropForeign('fk_staff_id');
			$table->dropForeign('fk_status_id');
		});
	}

}
