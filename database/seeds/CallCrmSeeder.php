<?php

use Illuminate\Database\Seeder;

class CallCrmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(App\Modules\Crm\Database\Seeds\CrmDefaultSettingsSeeder::class);
    }
}
