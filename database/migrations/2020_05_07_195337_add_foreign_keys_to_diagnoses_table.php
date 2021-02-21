<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToDiagnosesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('diagnoses', function(Blueprint $table)
		{
			$table->foreign('diagnose_item_id', 'diagnoses_ibfk_1')->references('id')->on('diagnose_items')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('appointment_id', 'fk_appointment_idx')->references('id')->on('appointments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('diagnoses', function(Blueprint $table)
		{
			$table->dropForeign('diagnoses_ibfk_1');
			$table->dropForeign('fk_appointment_idx');
		});
	}

}
