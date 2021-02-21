<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('total_ammount', 50)->nullable()->after('appointment_id');
            $table->string('remaining_payment', 50)->nullable()->after('total_ammount');
            $table->string('radeem_point', 50)->nullable()->after('remaining_payment');
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
            $table->dropColumn('total_ammount');
            $table->dropColumn('remaining_payment');
            $table->dropColumn('radeem_point');
        });
    }
}
