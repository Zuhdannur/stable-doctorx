<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesSubSub extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules_sub_sub', function (Blueprint $table) {
            $table->bigIncrements('id_sub_sub_modules');
            $table->integer('id_sub_modules');
            $table->integer('id_modul');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules_sub_sub');
    }
}
