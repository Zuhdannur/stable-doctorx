<?php

use Illuminate\Database\Seeder;

class CallFinanceDefaultSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(App\Modules\Accounting\Database\Seeds\FinanceDefaultSeeder::class);
    }
}
