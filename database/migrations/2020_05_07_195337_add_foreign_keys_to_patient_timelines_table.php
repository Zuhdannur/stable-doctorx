<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPatientTimelinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('patient_timelines', function(Blueprint $table)
		{
			$table->foreign('patient_id', 'fkt_patient_id')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('patient_timelines', function(Blueprint $table)
		{
			$table->dropForeign('fkt_patient_id');
		});
	}

}
