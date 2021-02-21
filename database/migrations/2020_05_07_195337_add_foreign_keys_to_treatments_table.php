<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTreatmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('treatments', function(Blueprint $table)
		{
			$table->foreign('patient_id', 'treatments_ibfk_1')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('staff_id', 'treatments_ibfk_2')->references('id')->on('staff')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('status_id', 'treatments_ibfk_3')->references('id')->on('appointment_status')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('treatments', function(Blueprint $table)
		{
			$table->dropForeign('treatments_ibfk_1');
			$table->dropForeign('treatments_ibfk_2');
			$table->dropForeign('treatments_ibfk_3');
		});
	}

}
