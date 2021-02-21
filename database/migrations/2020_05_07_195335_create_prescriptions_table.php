<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePrescriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prescriptions', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('patient_id')->index('fk_patient_idz');
			$table->integer('appointment_id')->index('fk_appointment_id');
			$table->integer('service_id')->nullable();
			$table->string('notes')->nullable();
			$table->text('complaint', 65535)->nullable();
			$table->text('treatment_history', 65535)->nullable();
			$table->integer('timeline_id')->nullable();
			$table->string('inform_concern', 200)->nullable();
			$table->string('signature_concern', 200)->nullable();
			$table->timestamps();
			$table->integer('created_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('prescriptions');
	}

}
