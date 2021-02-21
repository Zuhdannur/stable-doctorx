<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientTimelineDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('patient_timeline_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('timeline_id')->index('fk_timeline_id');
			$table->string('document', 200);
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
		Schema::drop('patient_timeline_details');
	}

}
