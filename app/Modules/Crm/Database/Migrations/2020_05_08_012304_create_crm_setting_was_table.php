<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrmSettingWasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crm_setting_was', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 100);
            $table->string('api_url', 250)->nullable();
            $table->json('settings');
            $table->text('wa_message');
            $table->tinyInteger('is_active')->default(0);
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
        Schema::dropIfExists('crm_setting_was');
    }
}
