<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAppointmentsCopyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('appointments_copy', function(Blueprint $table)
		{
			$table->foreign('appointment_type_id', 'appointments_copy_ibfk_1')->references('id')->on('admission_types')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('patient_id', 'appointments_copy_ibfk_2')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('staff_id', 'appointments_copy_ibfk_3')->references('id')->on('staff')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('status_id', 'appointments_copy_ibfk_4')->references('id')->on('appointment_status')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('appointments_copy', function(Blueprint $table)
		{
			$table->dropForeign('appointments_copy_ibfk_1');
			$table->dropForeign('appointments_copy_ibfk_2');
			$table->dropForeign('appointments_copy_ibfk_3');
			$table->dropForeign('appointments_copy_ibfk_4');
		});
	}

}
