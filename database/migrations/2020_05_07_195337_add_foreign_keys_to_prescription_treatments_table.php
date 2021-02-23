<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPrescriptionTreatmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('prescription_treatments', function(Blueprint $table)
		{
			$table->foreign('prescription_id', 'prescription_treatments_ibfk_1')->references('id')->on('prescriptions')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('services_id', 'prescription_treatments_ibfk_2')->references('id')->on('services')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->foreign('services_id', 'prescription_treatments_ibfk_3')->references('id')->on('services')->onUpdate('CASCADE')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('prescription_treatments', function(Blueprint $table)
		{
			$table->dropForeign('prescription_treatments_ibfk_1');
			$table->dropForeign('prescription_treatments_ibfk_2');
			$table->dropForeign('prescription_treatments_ibfk_3');
		});
	}

}
