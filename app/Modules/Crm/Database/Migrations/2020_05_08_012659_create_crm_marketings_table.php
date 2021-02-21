<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmMarketingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_marketings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',250);
            $table->string('name',150);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('discount');
            $table->integer('point');
            $table->tinyInteger('is_active');
            $table->timestamps();
            $table->softDeletesTz();
            $table->integer('created_by');
            $table->integer('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_marketings');
    }
}
