<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAssignedRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('assigned_roles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('role_id')->unsigned()->index();
			$table->integer('entity_id')->unsigned();
			$table->string('entity_type', 194);
			$table->integer('restricted_to_id')->unsigned()->nullable();
			$table->string('restricted_to_type', 194)->nullable();
			$table->integer('scope')->nullable()->index();
			$table->index(['entity_id','entity_type','scope'], 'assigned_roles_entity_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('assigned_roles');
	}

}
