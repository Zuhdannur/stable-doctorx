<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPatientMediaInfoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('patient_media_info', function(Blueprint $table)
		{
			$table->foreign('media_id', 'fk_media_id')->references('id')->on('attribute_info_media')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('patient_id', 'fk_patient_ids')->references('id')->on('patients')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('patient_media_info', function(Blueprint $table)
		{
			$table->dropForeign('fk_media_id');
			$table->dropForeign('fk_patient_ids');
		});
	}

}
