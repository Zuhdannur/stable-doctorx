<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAppointmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('appointments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('patient_id')->index('fk_patient_id');
			$table->string('appointment_no', 100)->nullable();
			$table->boolean('seq')->nullable();
			$table->dateTime('date');
			$table->integer('room_id')->nullable();
			$table->integer('staff_id')->nullable()->index('fk_staff_id');
			$table->text('notes', 65535)->nullable();
			$table->boolean('status_id')->index('fk_status_id');
			$table->date('next_appointment_date')->nullable();
			$table->text('next_appointment_notes', 65535)->nullable();
			$table->timestamps();
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('appointments');
	}

}
