<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_memberships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('patient_id');
            $table->smallInteger('ms_membership_id')->unsigned();
            $table->integer('total_point')->default(0);
            $table->timestamps();
            $table->softDeletesTz();
            $table->integer('create_by')->default(0);
            $table->integer('updated_by')->default(0);

            $table->foreign('ms_membership_id')
            ->references('id')->on('crm_ms_memberships')
            ->onUpdate('cascade')
            ->onDelete('restrict');

            $table->foreign('patient_id')
            ->references('id')->on('patients')
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
        Schema::dropIfExists('crm_memberships');
    }
}
