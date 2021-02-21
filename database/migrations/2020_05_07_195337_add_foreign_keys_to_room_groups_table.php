<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToRoomGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('room_groups', function(Blueprint $table)
		{
			$table->foreign('floor_id', 'fk_floor_id')->references('id')->on('floors')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('room_groups', function(Blueprint $table)
		{
			$table->dropForeign('fk_floor_id');
		});
	}

}
