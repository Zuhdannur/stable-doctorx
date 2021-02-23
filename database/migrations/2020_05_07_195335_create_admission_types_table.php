<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdmissionTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('admission_types', function(Blueprint $table)
		{
			$table->boolean('id')->primary();
			$table->string('name', 100)->unique('u_appointment_name');
			$table->string('route', 100);
			$table->integer('role_id')->unsigned()->index('fk_role_id');
			$table->integer('room_group_id')->nullable()->index('fk_room_group_idx');
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
		Schema::drop('admission_types');
	}

}
