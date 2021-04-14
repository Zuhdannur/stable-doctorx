<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRecordMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_record_mapping', function (Blueprint $table) {
            $table->bigIncrements('id_mapping');
            $table->integer('appointment_id');
            $table->string('jenis_tindakan')->nullable();
            $table->string('jenis_bahan')->nullable();
            $table->integer('jumlah')->default(0);
            $table->text('lokasi')->nullable();
            $table->text('catatan')->nullable();
            $table->text('canvas')->nullable();
            $table->text('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_record_mapping');
    }
}
