<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientMediaInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('patient_media_info', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('patient_id')->index('fk_patient_ids');
			$table->boolean('media_id')->nullable()->index('fk_media_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('patient_media_info');
	}

}
