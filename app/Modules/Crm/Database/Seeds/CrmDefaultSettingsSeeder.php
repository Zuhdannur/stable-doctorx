<?php

namespace App\Modules\Crm\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CrmDefaultSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('crm_setting_was')->insert([
            [
                'id' => '1',
                'name' => 'Woo Wandroid', 
                'api_url' => 'https://onesignal.com/api/v1/notifications',
                'settings' => "{\"cs_id\":\"61d6c8c9-36ec-4709-800b-c3766dbd0fa6\"}", 
                'wa_message' =>'Selamat Ulang Tahun yang ke [age] tahun [patient_name], Semoga Panjang Umur dan Sehat Selalu...', 
                'is_active' => '1',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => '2',
                'name' => 'Twillio',
                'api_url' => '-', 
                'settings' => "{\"sid\":\"AC94e4f2a113a215433ca4b839cb9dc16e\",\"token\":\"cc6b91a2db8c76450947666231ad5871\",\"senders\":\"+14155238886\"}",
                'wa_message' => 'Selamat Ulang Tahun yang ke [age] tahun [patient_name], Semoga Panjang Umur dan Sehat Selalu...',
                'is_active' => '0',
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        ]);
    }
}
