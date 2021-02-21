<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentHitoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('invoice_id');
            $table->string('total_pay', 50);
            $table->string('in_paid', 50);
            $table->timestamps();

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
        $table->dropForeign('payment_hitories_invoice_id_foreign');
        Schema::dropIfExists('payment_histories');
    }
}
