<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPrescriptionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('prescriptions', function(Blueprint $table)
		{
			$table->foreign('appointment_id', 'fk_appointment_id')->references('id')->on('appointments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('patient_id', 'fk_patient_idz')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('prescriptions', function(Blueprint $table)
		{
			$table->dropForeign('fk_appointment_id');
			$table->dropForeign('fk_patient_idz');
		});
	}

}
