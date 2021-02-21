<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyToTreatmentInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_invoices', function (Blueprint $table) {
            $table->dropForeign('treatment_invoices_ibfk_1');
            $table->dropForeign('treatment_invoices_ibfk_2');

            $table->foreign('treatment_id')
            ->references('id')->on('treatments')
            ->onUpdate('cascade')
            ->onDelete('restrict');

            $table->foreign('invoice_id')
            ->references('id')->on('invoices')
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
        Schema::table('treatment_invoices', function (Blueprint $table) {
            $table->dropForeign('treatment_invoices_treatment_id_foreign');
            $table->dropForeign('treatment_invoices_invoice_id_foreign');

            $table->foreign('treatment_id','treatment_invoices_ibfk_1')
            ->references('id')->on('treatments')
            ->onUpdate('restrict')
            ->onDelete('restrict');

            $table->foreign('invoice_id', 'treatment_invoices_ibfk_2')
            ->references('id')->on('invoices')
            ->onUpdate('restrict')
            ->onDelete('restrict');
        });
    }
}
