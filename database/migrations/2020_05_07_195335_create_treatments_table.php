<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTreatmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('treatments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('patient_id')->index('fk_patient_id');
			$table->integer('appointment_id')->nullable();
			$table->string('treatment_no', 100)->nullable();
			$table->boolean('seq')->nullable();
			$table->dateTime('date');
			$table->integer('room_id')->nullable();
			$table->integer('staff_id')->nullable()->index('fk_staff_id');
			$table->text('notes', 65535)->nullable();
			$table->boolean('status_id')->index('fk_status_id');
			$table->integer('service_id')->nullable();
			$table->string('service_notes', 194)->nullable();
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
		Schema::drop('treatments');
	}

}
