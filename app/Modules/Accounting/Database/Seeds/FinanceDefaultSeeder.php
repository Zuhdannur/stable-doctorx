<?php

namespace App\Modules\Accounting\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinanceDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // seed for default type trx
        DB::transaction(function () {
            DB::table('finance_trx_types')->insert([
                ['id'=>'1' ,'label'=> 'Receive Payment'],
                ['id'=>'2' ,'label' => 'Purchase Invoice'],
                ['id'=>'3' ,'label' => 'Transfer Payment'],
                ['id'=>'4' ,'label' => 'Send Payment'],
                ['id'=>'5' ,'label' => 'Expanses'],
                ['id'=>'6' ,'label' => 'General Journal'],
                ['id'=>'7' ,'label' => 'Stock Opname'],
                ['id'=>'8' ,'label' => 'Biaya Payment'],
                ['id'=>'9' ,'label' => 'Sales Invoice'],
                ['id'=>'10','label' => 'Invoice'],
                ['id'=>'11','label' => 'Invoice Payment'],
         ]);
    
            // DB::table('finance_account_categories')->truncate();
            DB::table('finance_account_categories')->insert([
    
                //type look at config finance_trx
    
                /** this ms category account default */
                [
                    'id' => '1',
                    'category_code' => '1-1',
                    'category_name' => 'Aktiva Lancar',
                    'parent_id' => '0',
                    'type' => '1',
                ],
                [
                    'id' => '2',
                    'category_code' => '1-2',
                    'category_name' => 'Aktiva Tetap',
                    'parent_id' => '0',
                    'type' => '1',
                ],
                [
                    'id' => '3',
                    'category_code' => '1-3',
                    'category_name' => 'Aktiva Lainnya',
                    'parent_id' => '0',
                    'type' => '1',
                ],
                [
                    'id' => '4',
                    'category_code' => '2-1',
                    'category_name' => 'Kewajiban Usaha',
                    'parent_id' => '0',
                    'type' => '2',
                ],
                [
                    'id' => '5',
                    'category_code' => '2-2',
                    'category_name' => 'Kewajiban Jangka Panjang',
                    'parent_id' => '0',
                    'type' => '2',
                ],
                [
                    'id' => '6',
                    'category_code' => '2-4',
                    'category_name' => 'Kewajiban Lainnya',
                    'parent_id' => '0',
                    'type' => '2',
                ],
                [
                    'id' => '7',
                    'category_code' => '3-1',
                    'category_name' => 'Ekuitas Usaha',
                    'parent_id' => '0',
                    'type' => '3',
                ],
                [
                    'id' => '8',
                    'category_code' => '4-1',
                    'category_name' => 'Pendapatan Usaha',
                    'parent_id' => '0',
                    'type' => '4',
                ],
                [
                    'id' => '9',
                    'category_code' => '4-2',
                    'category_name' => 'Pendapatan Diluar Usaha',
                    'parent_id' => '0',
                    'type' => '4',
                ],
                [
                    'id' => '10',
                    'category_code' => '5-1',
                    'category_name' => 'Beban',
                    'parent_id' => '0',
                    'type' => '5',
                ],
                [
                    'id' => '11',
                    'category_code' => '5-2',
                    'category_name' => 'Biaya',
                    'parent_id' => '0',
                    'type' => '5',
                ],
                /** end ms kategori akun */
    
                /** this is category account */
                [
                    'id' => '12',
                    'category_code' => '1-1-1',
                    'category_name' => 'Kas Dan Bank',
                    'parent_id' => '1', //Aktiva Lancar
                    'type' => '1',
                ],
                [
                    'id' => '13',
                    'category_code' => '1-1-2',
                    'category_name' => 'Piutang',
                    'parent_id' => '1',
                    'type' => '1',
                ],
                [
                    'id' => '14',
                    'category_code' => '1-1-3',
                    'category_name' => 'Persediaan',
                    'parent_id' => '1',
                    'type' => '1',
                ],
                [
                    'id' => '15',
                    'category_code' => '1-1-4',
                    'category_name' => 'Aktiva Lainnya',
                    'parent_id' => '1',
                    'type' => '1',
                ],
                [
                    'id' => '16',
                    'category_code' => '2-1-1',
                    'category_name' => 'Hutang',
                    'parent_id' => '4', //Kewajiban Usaha
                    'type' => '2',
                ],
                [
                    'id' => '17',
                    'category_code' => '2-1-2',
                    'category_name' => 'Pajak',
                    'parent_id' => '4', 
                    'type' => '2', 
                ],
                [
                    'id' => '18',
                    'category_code' => '3-1-1',
                    'category_name' => 'Ekuitas',
                    'parent_id' => '7', //Ekuitas Usaha
                    'type' => '3',
                ],
                [
                    'id' => '19',
                    'category_code' => '4-1-1',
                    'category_name' => 'Pendapatan Pelayanan Klinik',
                    'parent_id' => '8', //pendapatan usaha
                    'type' => '4',
                ],
                [
                    'id' => '20',
                    'category_code' => '5-1-1',
                    'category_name' => 'Beban Usaha',
                    'parent_id' => '10', //beban
                    'type' => '5',
                ],
                [
                    'id' => '21',
                    'category_code' => '5-2-1',
                    'category_name' => 'Biaya Usaha',
                    'parent_id' => '11', //biaya
                    'type' => '5',
                ],
                /** end category account*/
            ]);
    
            /** default finance account */
            DB::table('finance_accounts')->insert([
                [
                    'id' => '1',
                    'account_name' => 'Kas',
                    'account_code' => '1-1-10001',
                    'account_category_id' => '12',
                    'balance' => '0',
                    'description' => 'Ini adalah akun kas',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '2',
                    'account_name' => 'Piutang Usaha',
                    'account_code' => '1-1-20001',
                    'account_category_id' => '13',
                    'balance' => '0',
                    'description' => 'Ini adalah akun piutang usaha',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '3',
                    'account_name' => 'Persediaan Obat',
                    'account_code' => '1-1-30001',
                    'account_category_id' => '14',
                    'balance' => '0',
                    'description' => 'Ini adalah akun persediaan obat',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '4',
                    'account_name' => 'PPN Masukan',
                    'account_code' => '1-1-40001',
                    'account_category_id' => '15',
                    'balance' => '0',
                    'description' => 'Ini adalah akun ppn masukan',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '5',
                    'account_name' => 'Hutang Usaha',
                    'account_code' => '2-1-10001',
                    'account_category_id' => '16',
                    'balance' => '0',
                    'description' => 'Ini adalah akun hutang usaha',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '6',
                    'account_name' => 'PPN Keluaran',
                    'account_code' => '2-1-20001',
                    'account_category_id' => '17',
                    'balance' => '0',
                    'description' => 'Ini adalah akun PPN Keluaran',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '7',
                    'account_name' => 'Ekuitas Saldo Awal',
                    'account_code' => '3-1-10001',
                    'account_category_id' => '18',
                    'balance' => '0',
                    'description' => 'Ini adalah akun ekuitas saldo awal',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '8',
                    'account_name' => 'Pendapatan Klinik',
                    'account_code' => '4-1-10001',
                    'account_category_id' => '19',
                    'balance' => '0',
                    'description' => 'Ini adalah akun pendapatan klinik',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '9',
                    'account_name' => 'Radeem Point Pelanggan',
                    'account_code' => '5-1-100001',
                    'account_category_id' => '20',
                    'balance' => '0',
                    'description' => 'Ini adalah akun radeem point pelanggan',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '10',
                    'account_name' => 'Diskon Penjualan',
                    'account_code' => '5-1-10002',
                    'account_category_id' => '20',
                    'balance' => '0',
                    'description' => 'Ini adalah akun diskon penjualan',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '11',
                    'account_name' => 'Penyesuaian Persediaan Obat',
                    'account_code' => '5-1-10003',
                    'account_category_id' => '20',
                    'balance' => '0',
                    'description' => 'Ini adalah akun penyesuaian persediaan obat',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '12',
                    'account_name' => 'Beban Pokok Pendapatan',
                    'account_code' => '5-1-10004',
                    'account_category_id' => '20',
                    'balance' => '0',
                    'description' => 'Ini adalah akun beban pokok pendapatan',
                    'created_at' => \Carbon\Carbon::now()
                ],
                [
                    'id' => '13',
                    'account_name' => 'Biaya Kirim Pembelian',
                    'account_code' => '5-2-10001',
                    'account_category_id' => '21',
                    'balance' => '0',
                    'description' => 'Ini adalah akun biaya pembelian',
                    'created_at' => \Carbon\Carbon::now()
                ],
            ]);
    
            DB::table('finance_taxes')->insert([
                [
                    'id' => '1',
                    'tax_name' => 'PPN',
                    'account_tax_sales' => 6, //PPN Keluaran
                    'account_tax_purchase' => 4, //PPN Masukan
                    'percentage' => 10,
                    'created_at' => \Carbon\Carbon::now(),
                ]
            ]);
        });
    }
}
