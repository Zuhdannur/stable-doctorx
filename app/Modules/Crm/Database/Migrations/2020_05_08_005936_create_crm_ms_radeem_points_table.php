<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmMsRadeemPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_ms_radeem_points', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('code', 250)->unique();
            $table->integer('point')->default(0);
            $table->integer('nominal_gift')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_ms_radeem_points');
    }
}
