<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIncentivesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('incentives', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('name', 128);
			$table->string('description', 191)->nullable();
			$table->float('product_incentive', 10, 0)->default(0);
			$table->boolean('point_value')->default(0);
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
		Schema::drop('incentives');
	}

}
