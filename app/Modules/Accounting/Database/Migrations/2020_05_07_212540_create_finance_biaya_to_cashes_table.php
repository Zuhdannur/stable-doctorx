<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceBiayaToCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_biaya_to_cashes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('transaction_id')->unsigned();
            $table->bigInteger('biaya_trx_id')->unsigned();
            $table->timestamps();

            $table->foreign('biaya_trx_id')
            ->references('id')->on('finance_biaya_trxes')
            ->onUpdate('cascade')
            ->onDelete('restrict');

            $table->foreign('transaction_id')
            ->references('id')->on('finance_transactions')
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
        Schema::dropIfExists('finance_biaya_to_cashes');
    }
}
