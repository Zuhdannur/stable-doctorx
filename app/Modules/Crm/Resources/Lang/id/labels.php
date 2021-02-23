<?php

return [
    'settings' => [
        'membership' => [
            'main' => 'Master Membership',
            'create' => 'Buat Data Membership',
            'edit' => 'Ubah Data Membership',
            'table' => [
                'name' => 'Nama Membership',
                'point' => 'Point Per Transaksi',
                'min_trx' => 'Min. Transaksi',
            ],
        ],
        'wa' => [
            'main' => 'Setting WhatssApp',
            'msg' => 'Pesan WA untuk Menu Ultah',
            'vendor' => 'Vendor Whatssapp',
        ]
    ],
    'membership' => [
        'main' => 'Data Membership',
        'create' => 'Tambah Membership',
        'edit' => 'Ubah Membership',
        'show' => 'Detail Membership',
        'table' => [
            'unique_id' => 'Id Pasien',
            'membership' => 'Jenis Membership',
            'point' => 'Jumlah Point',
        ]
    ],
    'point' => [
        'obat' => [
            'main' => 'Kelola Point Obat',
            'edit' => 'Ubah Data Point Obat',
            'table' => [
                'product_code' => 'Kode Obat',
                'name' => 'Nama Obat',
                'point' => 'Point',
                'min_qty' => 'Min. Jml Pembelian',
            ]
        ],
        'service' => [
            'main' => 'Kelola Point Service',
            'edit' => 'Ubah Point Service',
            'table' => [
                'point' => 'Point',
            ]
        ],
        'radeem' => [
            'main' => 'Kelola Master Radeem Point',
            'create' => 'Tambah Data Master Radeem Point',
            'edit' => 'Ubah Data Master Radeem Point',
            'table' => [
                'code' => 'Kode',
                'point' => 'Jumlah Point',
                'nominal_gift' => 'Nominal Penukaran',
            ]
        ]
    ],

    'radeem' => [
        'main' => 'Radeem Point',
        'form' => 'Form Penukaran Point',
        'radeem' => 'Tukar Point',
        'data_patient' => 'Data Pasien',
        'patient_name' => 'Nama Pasien',
        'dob' => 'Tanggal Lahir',
        'phone_number' => 'Nomor Telp.',
        'membership' => 'Jenis Membership',
        'point' => 'Total Point',
        'table' => [
            'date' =>  'Tanggal',
            'items_code' => 'Kode Item',
            'point_item' => 'Point Item',
            'ammount' => 'Jumlah Item',
            'point_total' =>  'Total Point',
            'nominal_total' =>  'Total Nominal',
            'user_app' =>  'User APP',
        ]
    ],
    'marketing' => [
        'main' => 'Kelola Marketing Activity',
        'create' => 'Tambah Data Marketing Activity',
        'edit' => 'Ubah Data  Marketing Activity',
        'table' => [
            'code' => 'Kode',
            'name' => 'Nama Marketing',
            'start_date' => 'Tangal Mulai',
            'end_date' => 'Tangal Berakhir',
            'discount' => 'Potongan Harga(%)',
            'point' => 'Point',
        ]
    ],

    'birthday' => [
        'edit' => 'Edit/Tambah No Whatsapp'
    ]
];