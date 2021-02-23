<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIndonesiaDistrictsOldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('indonesia_districts_old', function(Blueprint $table)
		{
			$table->foreign('city_id', 'districts_city_id_foreign')->references('id')->on('indonesia_cities_old')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('indonesia_districts_old', function(Blueprint $table)
		{
			$table->dropForeign('districts_city_id_foreign');
		});
	}

}
