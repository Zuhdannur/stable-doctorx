<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToAllT extends Migration
{

    public function data() {
        return array(
            "appointments",
            "bookings",
            "crm_marketings",
            "crm_memberships",
            "crm_ms_memberships",
            "crm_ms_radeem_points",
            "crm_radeem_points",
            "finance_accounts",
            "finance_transactions",
            "floors",
            "incentives",
            "invoices",
            "patients",
            "users",
            "staff",
            "rooms",
            "room_groups",
            "product_categories",
            "products"
        );
    }

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {

        foreach ($this->data() as $item) {
            Schema::table($item, function (Blueprint $table) {
                $table->integer('id_klinik')->nullable();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->data() as $item) {
            Schema::table($item, function (Blueprint $table) {
                $table->dropColumn('id_klinik');
            });
        }
    }
}
