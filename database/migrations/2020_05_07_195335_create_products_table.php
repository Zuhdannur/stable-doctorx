<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('products', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->char('code', 12);
			$table->string('name', 128);
			$table->integer('category_id')->index('fk_category_id');
			$table->float('price', 10);
			$table->integer('quantity')->unsigned()->nullable()->default(0);
			$table->boolean('is_active', 1)->default('b\'1\'');
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
		Schema::drop('products');
	}

}
