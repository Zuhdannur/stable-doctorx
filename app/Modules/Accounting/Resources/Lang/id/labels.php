<?php

return [
    'account' => [
        'account' => 'Data Akun Keuangan',
        'edit'         => 'Ubah Akun',
        'create'         => 'Buat Akun',

        'table' => [
            'account_code' => 'Kode Akun',
            'account_name' => 'Nama Akun',
            'account_category' => 'Kategori Akun',
            'balance' => 'Saldo',
        ]
    ],
    
    'journal' => [
        'create' => 'Buat Jurnal Umum',
        'show' => 'Jurnal Keuangan',
        'general' => 'Jurnal Umum',

        'table' => [
            'transaction_code' => 'Kode Transaksi',
            'contact' => 'Kontak',
            'debit' => 'Debit',
            'credit' => 'Kredit',
            'balance' => 'Saldo',
        ]
    ],

    'cash' => [
        'cash' => 'Kas dan Bank',
        'edit'         => 'Ubah Akun',
        'create'       => 'Tambah Akun Kas dan Bank',
        'receipt'       => 'Terima Uang',
        'transfer'       => 'Transfer Uang',
        'show_transfer'       => 'Detail Transfer',
        'show_receipt'       => 'Detail Terima Uang',
        'show_send'       => 'Detail Pengiriman Uang',
        'send'       => 'Kirim Uang',
        'journal' => 'Jurnal Keungan',

        'table' => [
            'name' => 'Nama Akun Kas/Bank',
            'acc_code' => 'Kode Akun',
            'balance' => 'Saldo',
        ]
    ],

    'settings' => [
        'categories' => [
            'main' => 'Kelola Data Kategori Akun',
            'edit' => 'Edit Data Kategori',
            'create' => 'Tambah Data Kategori'
        ],
        
        'ms_categories' => [
            'main' => 'Kelola Master Data Kategori Akun',
            'edit' => 'Edit Master Kategori',
            'create' => 'Tambah Data Master Kategori'
        ],

        'tax' => [
            'main' => 'Kelola Data Pajak',
            'edit' => 'Edit Data Pajak',
            'create' => 'Tambah Data Pajak'
        ],

        'table_ms_categories' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'type' => 'Tipe Kategori',
        ],

        'table_categories' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'ms' => 'Master Kategori',
            'type' => 'Tipe Kategori',
        ],

        'table_tax' => [
            'name' => 'Nama',
            'percentage' => 'Persentase Pajak',
            'purchasing' => 'Akun Pajak Pembelian',
            'selling' => 'Akun Pajak Penjualan',
        ],
    ],

    'biaya' => [
        'main' => 'Biaya Pengeluaran',
        'create' => 'Buat Biaya Pengeluaran',
        'show' => 'Detail Biaya Pengeluaran',
        'pay' => 'Bayar Pengeluaran',
        'paymentDetail' => 'Detail Pembayaran Biaya',
        'table_title' => 'Data Biaya',
        'table' => array(
            'date' => 'tanggal',
            'code' => 'Kode Transaksi',
            'receiver' => 'Penerima',
            'status' => 'Status',
            'due_date' => 'Jatuh Tempo',
            'bill' => 'Sisa tagihan',
            'total' => 'Total',
        )
    ],

    'purchase' => [
        'main' => 'Data Pembelian',
        'create' => 'Buat Pembelian Baru',
        'show' => 'Detail  Pembelian',
        'pay' => 'Bayar Pembelian',
        'table_title' => 'Data Pembelian',
        'paymentDetail' => 'Detail Pembayarn Pembelian',
        'table' => array(
            'date' => 'tanggal',
            'code' => 'Kode Transaksi',
            'receiver' => 'Penerima',
            'status' => 'Status',
            'due_date' => 'Jatuh Tempo',
            'bill' => 'Sisa tagihan',
            'total' => 'Total',
        )
    ],
    'reports' => [
        'neraca' => 'Neraca Keuangan',
        'lost_profit' => 'Laporan Laba Rugi',
    ]
];
