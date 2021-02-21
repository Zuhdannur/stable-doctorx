<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAbilitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('abilities', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 100);
			$table->string('title', 194);
			$table->string('group', 191)->nullable();
			$table->integer('entity_id')->unsigned()->nullable();
			$table->string('entity_type', 194)->nullable();
			$table->boolean('only_owned')->default(0);
			$table->text('options')->nullable();
			$table->integer('scope')->nullable()->index();
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
		Schema::drop('abilities');
	}

}
