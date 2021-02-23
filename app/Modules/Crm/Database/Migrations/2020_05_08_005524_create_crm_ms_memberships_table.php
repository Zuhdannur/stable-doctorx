<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmMsMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_ms_memberships', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('name', 200);
            $table->mediumInteger('point');
            $table->mediumInteger('min_trx');
            $table->timestamps();
            $table->softDeletesTz();
            $table->integer('create_by')->default(0);
            $table->integer('updated_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_ms_memberships');
    }
}
