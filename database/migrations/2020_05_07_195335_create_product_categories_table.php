<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_categories', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 128);
			$table->boolean('is_active', 1)->nullable()->default('b\'1\'');
			$table->integer('parent_id')->nullable()->index('fk_parent_id');
			$table->integer('left')->unsigned()->nullable();
			$table->integer('right')->unsigned()->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('product_categories');
	}

}
