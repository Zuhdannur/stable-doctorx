<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePatientFlagsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('patient_flags', function(Blueprint $table)
		{
			$table->boolean('id')->primary();
			$table->string('name', 100);
			$table->string('color_code', 100);
			$table->timestamps();
			$table->unique(['name','color_code'], 'u_name_flag');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('patient_flags');
	}

}
