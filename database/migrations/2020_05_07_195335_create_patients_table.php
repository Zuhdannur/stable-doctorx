<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('patients', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('patient_unique_id', 11);
			$table->string('patient_name', 64);
			$table->boolean('age')->nullable();
			$table->string('birth_place', 100)->nullable();
			$table->date('dob');
			$table->string('phone_number', 100);
			$table->string('email', 100)->nullable();
			$table->string('gender', 10);
			$table->boolean('religion_id')->nullable()->index('fk_religion_id');
			$table->boolean('blood_id')->index('fk_blood_id');
			$table->text('address', 65535)->nullable();
			$table->string('photo')->nullable();
			$table->char('city_id', 4)->nullable()->index('fk_city_id');
			$table->char('district_id', 7)->nullable()->index('fk_district_id');
			$table->char('village_id', 10)->nullable()->index('fk_village_idx');
			$table->integer('zip_code')->nullable();
			$table->boolean('info_id')->nullable();
			$table->boolean('work_id')->nullable()->index('fk_work_id');
			$table->boolean('patient_flag_id')->nullable()->index('fks_patient_idx');
			$table->enum('old_patient', array('y','n'))->nullable()->default('n');
			$table->string('hobby', 100)->nullable();
			$table->timestamps();
			$table->dateTime('disabled_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('patients');
	}

}
