<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPatientTimelineDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('patient_timeline_details', function(Blueprint $table)
		{
			$table->foreign('timeline_id', 'fk_timeline_id')->references('id')->on('patient_timelines')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('patient_timeline_details', function(Blueprint $table)
		{
			$table->dropForeign('fk_timeline_id');
		});
	}

}
