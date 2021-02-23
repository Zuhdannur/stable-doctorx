<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnWaMessageReminderToCrmSettingWasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('crm_setting_was', function (Blueprint $table) {
            $table->text('wa_message_reminder')->nullable(true)->default('Hy, [patient_name]. Anda memiliki jadwal konsultasi/treatment di [app_name], pada [date]');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('crm_setting_was', function (Blueprint $table) {
            $table->dropColumn('wa_message_reminder');
        });
    }
}
