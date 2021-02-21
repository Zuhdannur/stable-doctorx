<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientTimelinesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('patient_timelines', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('patient_id')->index('fkt_patient_id');
			$table->string('title', 200);
			$table->date('timeline_date');
			$table->string('description', 200);
			$table->boolean('status', 1)->default('b\'1\'');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('patient_timelines');
	}

}
