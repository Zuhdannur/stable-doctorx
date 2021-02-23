<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('services', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->char('code', 12);
			$table->string('name', 128);
			$table->boolean('category_id')->index('fk_category_id');
			$table->float('price', 10)->nullable();
			$table->boolean('is_active', 1)->nullable()->default('b\'1\'');
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
		Schema::drop('services');
	}

}
