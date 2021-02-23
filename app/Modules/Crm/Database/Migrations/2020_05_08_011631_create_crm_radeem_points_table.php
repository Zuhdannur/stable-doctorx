<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmRadeemPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_radeem_points', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('membership_id')->unsigned();
            $table->string('item_code', 250);
            $table->mediumInteger('ammount');
            $table->integer('point')->default(0);
            $table->integer('nominal')->default(0);
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);

            $table->foreign('membership_id')
            ->references('id')->on('crm_memberships')
            ->onUpdate('cascade')
            ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('crm_radeem_points');
    }
}
