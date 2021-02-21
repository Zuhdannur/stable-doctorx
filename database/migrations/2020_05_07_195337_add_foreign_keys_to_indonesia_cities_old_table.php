<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIndonesiaCitiesOldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('indonesia_cities_old', function(Blueprint $table)
		{
			$table->foreign('province_id', 'cities_province_id_foreign')->references('id')->on('indonesia_provinces_old')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('indonesia_cities_old', function(Blueprint $table)
		{
			$table->dropForeign('cities_province_id_foreign');
		});
	}

}
