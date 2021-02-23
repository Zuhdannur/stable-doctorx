<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('staff', function(Blueprint $table)
		{
			$table->foreign('department_id', 'fk_department_id')->references('id')->on('departments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('designation_id', 'fk_designation_id')->references('id')->on('staff_designations')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id', 'fk_user_idxs')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('staff', function(Blueprint $table)
		{
			$table->dropForeign('fk_department_id');
			$table->dropForeign('fk_designation_id');
			$table->dropForeign('fk_user_idxs');
		});
	}

}
