<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyTreatmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropForeign('treatments_ibfk_1');
            $table->dropForeign('treatments_ibfk_2');
            $table->dropForeign('treatments_ibfk_3');

            $table->foreign('patient_id')
            ->references('id')->on('patients')
            ->onUpdate('cascade')
            ->onDelete('restrict');

            $table->foreign('staff_id')
            ->references('id')->on('staff')
            ->onUpdate('cascade')
            ->onDelete('restrict');

            $table->foreign('status_id')
            ->references('id')->on('appointment_status')
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
        Schema::table('treatments', function (Blueprint $table) {
            $table->dropForeign('treatments_patient_id_foreign');
            $table->dropForeign('treatments_staff_id_foreign');
            $table->dropForeign('treatments_status_id_foreign');

            $table->foreign('patient_id','treatments_ibfk_1')
            ->references('id')->on('patients')
            ->onUpdate('restrict')
            ->onDelete('restrict');

            $table->foreign('staff_id','treatments_ibfk_2')
            ->references('id')->on('staff')
            ->onUpdate('restrict')
            ->onDelete('restrict');

            $table->foreign('status_id', 'treatments_ibfk_3')
            ->references('id')->on('appointment_status')
            ->onUpdate('restrict')
            ->onDelete('restrict');
        });
    }
}
