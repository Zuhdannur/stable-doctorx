<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIndonesiaProvincesOldTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('indonesia_provinces_old', function(Blueprint $table)
		{
			$table->char('id', 2)->primary();
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
		Schema::drop('indonesia_provinces_old');
	}

}
