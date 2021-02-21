<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToAdmissionTypesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('admission_types', function(Blueprint $table)
		{
			$table->foreign('role_id', 'fk_role_id')->references('id')->on('roles')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('room_group_id', 'fk_room_group_idx')->references('id')->on('room_groups')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('admission_types', function(Blueprint $table)
		{
			$table->dropForeign('fk_role_id');
			$table->dropForeign('fk_room_group_idx');
		});
	}

}
