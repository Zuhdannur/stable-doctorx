<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateServiceCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('service_categories', function(Blueprint $table)
		{
			$table->boolean('id')->primary();
			$table->string('name', 100);
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
		Schema::drop('service_categories');
	}

}
