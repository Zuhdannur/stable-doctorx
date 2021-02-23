<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientQueuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('patient_queues', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->boolean('appointment_type_id')->index('fk_appointment_type_idx');
			$table->integer('patient_id')->index('fk_patient_idx');
			$table->dateTime('date')->default('0000-00-00 00:00:00');
			$table->boolean('call', 1)->nullable();
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
		Schema::drop('patient_queues');
	}

}
