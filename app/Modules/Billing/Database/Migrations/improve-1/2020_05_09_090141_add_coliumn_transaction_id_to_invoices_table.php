<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColiumnTransactionIdToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->bigInteger('transaction_id')->after('invoice_no')->nullable()->unsigned();

            $table->foreign('transaction_id')
            ->references('id')->on('finance_transactions')
            ->onUpdate('set null')
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
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign('invoices_transaction_id_foreign');
            $table->dropColumn('transaction_id');
        });
    }
}
