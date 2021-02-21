<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIndonesiaDistrictsOldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('indonesia_districts_old', function(Blueprint $table)
		{
			$table->char('id', 7)->primary();
			$table->char('city_id', 4)->index('districts_city_id_foreign');
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
		Schema::drop('indonesia_districts_old');
	}

}
