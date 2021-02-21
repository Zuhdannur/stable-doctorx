<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppointmentsCopyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('appointments_copy', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('patient_id')->index('fk_patient_id');
			$table->string('appointment_no', 100);
			$table->boolean('appointment_type_id')->nullable()->index('fk_appointment_type');
			$table->dateTime('date');
			$table->integer('room_id')->nullable();
			$table->integer('staff_id')->nullable()->index('fk_staff_id');
			$table->boolean('status_id')->index('fk_status_id');
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
		Schema::drop('appointments_copy');
	}

}
