<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePrescriptionDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prescription_details', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('prescription_id')->index('fk_prescription_id');
			$table->integer('product_id')->nullable()->index('product_id');
			$table->string('name', 194);
			$table->string('instruction', 194)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('prescription_details');
	}

}
