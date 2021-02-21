<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTreatmentDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('treatment_details', function(Blueprint $table)
		{
			$table->foreign('treatment_id', 'treatment_details_ibfk_1')->references('id')->on('treatments')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('treatment_details', function(Blueprint $table)
		{
			$table->dropForeign('treatment_details_ibfk_1');
		});
	}

}
