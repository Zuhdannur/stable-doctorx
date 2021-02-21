<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInvoiceIdToCrmRadeemPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_radeem_points', function (Blueprint $table) {
            $table->integer('invoice_id')->unsigned()->nullable(true)->default(null)->after('membership_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_radeem_points', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
        });
    }
}
