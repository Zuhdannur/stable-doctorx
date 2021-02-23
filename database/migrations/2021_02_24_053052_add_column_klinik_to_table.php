<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnKlinikToTable extends Migration
{

    public function data() {
        return array(
            "service_categories",
            "services"
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
