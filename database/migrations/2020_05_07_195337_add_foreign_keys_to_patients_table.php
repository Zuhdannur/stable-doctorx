<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPatientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('patients', function(Blueprint $table)
		{
			$table->foreign('blood_id', 'fk_blood_id')->references('id')->on('attribute_blood_banks')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('city_id', 'fk_city_id')->references('id')->on('indonesia_cities_old')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('district_id', 'fk_district_id')->references('id')->on('indonesia_districts_old')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('religion_id', 'fk_religion_id')->references('id')->on('attribute_religions')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('village_id', 'fk_village_idx')->references('id')->on('indonesia_villages_old')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('work_id', 'fk_work_id')->references('id')->on('attribute_works')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('patient_flag_id', 'fks_patient_idx')->references('id')->on('patient_flags')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('patients', function(Blueprint $table)
		{
			$table->dropForeign('fk_blood_id');
			$table->dropForeign('fk_city_id');
			$table->dropForeign('fk_district_id');
			$table->dropForeign('fk_religion_id');
			$table->dropForeign('fk_village_idx');
			$table->dropForeign('fk_work_id');
			$table->dropForeign('fks_patient_idx');
		});
	}

}
