<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('ability_id')->unsigned()->index();
			$table->integer('entity_id')->unsigned()->nullable();
			$table->string('entity_type')->nullable();
			$table->boolean('forbidden')->default(0);
			$table->integer('scope')->nullable()->index();
			$table->index(['entity_id','entity_type','scope'], 'permissions_entity_index');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('permissions');
	}

}
