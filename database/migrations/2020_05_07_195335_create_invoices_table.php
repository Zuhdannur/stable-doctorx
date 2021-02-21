<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvoicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('invoice_no', 128);
			$table->integer('patient_id')->index('fk_inv_patient_idx');
			$table->integer('appointment_id')->nullable()->index('appointment_id');
			$table->enum('status', array('0','1','2'))->nullable()->default('0');
			$table->text('note', 65535)->nullable();
			$table->boolean('tax')->nullable()->default(0);
			$table->boolean('discount')->nullable()->default(0);
			$table->date('date');
			$table->float('in_paid', 10, 0)->nullable()->default(0);
			$table->timestamps();
			$table->integer('created_by');
			$table->integer('updated_by')->nullable();
			$table->softDeletes();
			$table->integer('deleted_by')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoices');
	}

}
