<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIndonesiaCitiesOldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('indonesia_cities_old', function(Blueprint $table)
		{
			$table->char('id', 4)->primary();
			$table->char('province_id', 2)->index('cities_province_id_foreign');
			$table->string('name');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('indonesia_cities_old');
	}

}
