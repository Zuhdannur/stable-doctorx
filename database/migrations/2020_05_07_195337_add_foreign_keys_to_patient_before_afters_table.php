<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPatientBeforeAftersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('patient_before_afters', function(Blueprint $table)
		{
			$table->foreign('patient_id', 'fk_patient_idxg')->references('id')->on('patients')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('patient_before_afters', function(Blueprint $table)
		{
			$table->dropForeign('fk_patient_idxg');
		});
	}

}
