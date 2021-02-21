<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRoomGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('room_groups', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 64);
			$table->string('description', 128)->nullable();
			$table->boolean('floor_id')->index('fk_floor_id');
			$table->enum('type', array('APPOINTMENT','TREATMENT','BILLING'));
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
		Schema::drop('room_groups');
	}

}
