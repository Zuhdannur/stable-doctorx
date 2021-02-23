<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToIndonesiaVillagesOldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('indonesia_villages_old', function(Blueprint $table)
		{
			$table->foreign('district_id', 'villages_district_id_foreign')->references('id')->on('indonesia_districts_old')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('indonesia_villages_old', function(Blueprint $table)
		{
			$table->dropForeign('villages_district_id_foreign');
		});
	}

}
