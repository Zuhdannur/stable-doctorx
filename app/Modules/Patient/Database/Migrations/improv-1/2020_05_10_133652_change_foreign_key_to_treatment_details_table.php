<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyToTreatmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_details', function (Blueprint $table) {
            $table->dropForeign('treatment_details_ibfk_1');

            $table->foreign('treatment_id')
            ->references('id')->on('treatments')
            ->onDelete('cascade')
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
        Schema::table('treatment_details', function (Blueprint $table) {
            $table->dropForeign('treatment_details_treatment_id_foreign');

            $table->foreign('treatment_id', 'treatment_details_ibfk_1')
            ->references('id')->on('treatments')
            ->onDelete('restrict')
            ->onDelete('restrict');
        });
    }
}
