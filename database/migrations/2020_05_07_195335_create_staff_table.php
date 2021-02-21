<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaffTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staff', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('employee_id', 100)->unique('u_employee_id');
			$table->boolean('department_id')->nullable()->index('fk_department_id');
			$table->boolean('designation_id')->nullable()->index('fk_designation_id');
			$table->string('phone_number', 100)->nullable();
			$table->boolean('religion_id')->nullable();
			$table->boolean('blood_id')->nullable();
			$table->text('address', 65535)->nullable();
			$table->string('place_of_birth', 100)->nullable();
			$table->date('date_of_birth')->nullable();
			$table->boolean('marital_status')->nullable();
			$table->date('date_of_joining')->nullable();
			$table->date('date_of_leaving')->nullable();
			$table->string('gender', 10)->nullable();
			$table->string('note', 200)->nullable();
			$table->bigInteger('user_id')->unsigned()->index('fk_user_idxs');
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('staff');
	}

}
