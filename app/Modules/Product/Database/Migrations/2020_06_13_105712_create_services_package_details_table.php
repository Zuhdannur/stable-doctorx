<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesPackageDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_package_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_package_id');
            $table->integer('service_id');
            $table->integer('qty');
            $table->timestamps();

            $table->foreign('service_package_id')->references('id')->on('services_packages')
            ->onUpdate('cascade')
            ->onDelete('restrict');

            $table->foreign('service_id')->references('id')->on('services')
            ->onUpdate('cascade')
            ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services_package_details', function (Blueprint $table) {
            $table->dropForeign('services_package_details_service_id_foreign');
            $table->dropForeign('services_package_details_service_package_id_foreign');
        });
        Schema::dropIfExists('services_package_details');
    }
}
