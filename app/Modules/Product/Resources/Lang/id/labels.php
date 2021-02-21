<?php

return [
    'category' => [
        'management' => 'Kategori',
        'edit'         => 'Ubah Data',
        'create'         => 'Tambah Data',

        'table' => [
            'name' => 'Nama Kategori',
        ]
    ],
    'product' => [
        'management' => 'Produk',
        'edit'         => 'Ubah Data',
        'create'         => 'Tambah Data',
        'opname'         => 'Buat Stock Opname',

        'table' => [
            'name' => 'Nama Produk/Servis',
            'category' => 'Kategori',
            'code' => 'Kode',
            'purchase_price' => 'Harga Beli',
            'percentage_price_sales' => '% Harga Jual',
            'price' => 'Harga Jual',
            'stock' => 'Stock',
        ]
    ],
    'import' => [
        'management' => 'Import Produk',
        'create'         => 'Import Produk',
    ],
    'servicecategory' => [
        'management' => 'Service Kategori',
        'edit'         => 'Ubah Data',
        'create'         => 'Tambah Data',

        'table' => [
            'name' => 'Nama Kategori',
        ]
    ],
    'service' => [
        'management' => 'Service',
        'edit'         => 'Ubah Data',
        'create'         => 'Tambah Data',

        'table' => [
            'name' => 'Nama Service',
            'category' => 'Kategori',
            'code' => 'Kode',
            'price' => 'Harga',
        ]
    ],

    'supplier' => [
        'management' => 'Data Supplier',
        'edit'         => 'Ubah Data Supplier',
        'create'         => 'Tambah Data Supplier',
        'show'         => 'Detail Supplier',

        'table' => [
            'code' => 'Kode Supplier',
            'name' => 'Nama Supplier',
            'phone_number' => 'Nomer Telp',
            'company' => 'Nama Perusahaan',
            'company_phone_number' => 'No Tlp Perusahaan',
        ],
        
        'table-detail' => [
            'date' => 'Tanggal Transaksi',
            'trx_code' => 'Kode Transaki',
            'remain_payment' => 'Sisa Tagihan',
            'total' => 'Total Pembelian',
        ]
    ],
];
