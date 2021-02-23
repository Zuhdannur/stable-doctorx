<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIndonesiaVillagesOldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('indonesia_villages_old', function(Blueprint $table)
		{
			$table->char('id', 10)->primary();
			$table->char('district_id', 7)->index('villages_district_id_foreign');
			$table->string('name', 194);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('indonesia_villages_old');
	}

}
