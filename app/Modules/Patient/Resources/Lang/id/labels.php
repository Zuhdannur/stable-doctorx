<?php

return [
    'patient' => [
        'management' => 'Data Pasien',
        'edit'         => 'Ubah Pasien',
        'create'         => 'Tambah Pasien',

        'table' => [
            'name' => 'Nama Pasien',
            'unique_id' => 'Nomor ID',
            'phone_number' => 'No. Telepon',
            'gender' => 'Jen.Kel',
            'dob' => 'Tgl. Lahir',
            'age' => 'Usia',
            'input_date' => 'Tanggal Input'
        ]
    ],

    'appointment' => [
        'management' => 'Data Konsultasi',
        'edit'         => 'Ubah Konsultasi',
        'create'         => 'Tambah Konsultasi',

        'table' => [
            'app_no' => 'APN',
            'name' => 'Nama',
            'unique_id' => 'ID',
            'date' => 'Tanggal',
            'status' => 'Status',
            'notes' => 'Catatan',
            'room' => 'Ruangan',
            'floor' => 'Lantai',
            'pic' => 'Dokter'
        ]
    ],

    'treatment' => [
        'management' => 'Data Treatment',
        'edit'         => 'Ubah Treatment',
        'create'         => 'Tambah Treatment',

        'table' => [
            'app_no' => 'APN',
            'name' => 'Nama Pasien',
            'unique_id' => 'ID Pasien',
            'date' => 'Tanggal',
            'status' => 'Status',
            'pic' => 'Terapis',
            'notes' => 'Catatan'
        ]
    ],

    'prescription' => [
        'management' => 'Data Resep',
        'edit'         => 'Ubah Resep',
        'create'         => 'Tambah Resep',

        'table' => [
            'app_no' => 'APN',
            'name' => 'Nama Pasien',
            'unique_id' => 'ID Pasien',
            'date' => 'Tanggal',
            'status' => 'Status',
            'notes' => 'Catatan',
            'pic' => 'Dokter'
        ]
    ],

    'diagnoseitem' => [
        'management' => 'Data Diagnosa',
        'edit'         => 'Ubah Diagnosa',
        'create'         => 'Tambah Diagnosa',

        'table' => [
            'code' => 'Kode',
            'name' => 'Nama',
            'description' => 'Deskripsi'
        ]
    ],
];
