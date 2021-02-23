<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPrescriptionDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('prescription_details', function(Blueprint $table)
		{
			$table->foreign('prescription_id', 'fk_prescription_id')->references('id')->on('prescriptions')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('product_id', 'prescription_details_ibfk_1')->references('id')->on('products')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('prescription_details', function(Blueprint $table)
		{
			$table->dropForeign('fk_prescription_id');
			$table->dropForeign('prescription_details_ibfk_1');
		});
	}

}
