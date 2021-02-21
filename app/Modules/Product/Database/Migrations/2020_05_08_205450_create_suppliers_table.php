<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('supplier_code', 250)->nullable();
            $table->string('supplier_name', 100)->nullable();
            $table->string('birth_place', 75)->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('company_phone_number', 30)->nullable();
            $table->string('company_city_id', 4)->nullable();
            $table->string('company_district_id', 7)->nullable();
            $table->string('company_village_id', 10)->nullable();
            $table->string('company_address', 150)->nullable();
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
        Schema::dropIfExists('suppliers');
    }
}
