<?php

return [
    'type' => [
        'aktiva' => 1,
        'kewajiban' => 2,
        'ekuitas' => 3,
        'pendapatan' => 4,
        'beban' => 5,
    ],
    'type_list' => array(
        1 => 'Asset / Aktiva',
        2 => 'Kewajiban',
        3 =>'Ekuitas',
        4 =>'Pendapatan',
        5 =>'Beban',
    ),

    'default' => array(
        'cash' => 12,

        // account finance default
        'acc_cash' => 1,
        'acc_piutang' => 2,
        'acc_hutang' => 5,
        'acc_persediaan_obat' => 3,
        'acc_ekuitas' => 7,
        'acc_pendapatan' => 8,
        'acc_radeem' => 9,
        'acc_discount' => 10,
        'acc_beban_pokok' => 12,
    ),
];