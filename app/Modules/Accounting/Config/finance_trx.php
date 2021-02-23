<?php

return [
    // trx type default from finance_trx_types
    'trx_types' => [
        'receive' => 1,
        'purchase' => 2,
        'transfer' => 3,
        'send' => 4,
        'biaya' => 5,
        'general' => 6,
        'opname' => 7,
        'purchase_payment' => 8,
        'biaya_payment' => 9,
        'invoice' => 10,
        'invoice_payment' => 11,
    ],

    'label_trx_types' => [
        1 => 'Receive Payment',
        2 => 'Purchasing',
        3 => 'Transfer Payment',
        4 => 'Payment Send',
        5 => "Biaya Transaction",
        6 => 'General Journal',
        7 => 'Stock Opname',
        8 => 'Purchase Payment',
        9 => 'Biaya Payment',
        10 => 'Invouce Transaction',
        11 => 'Invoice Payment',
    ],

    'person_type' => [
        'general' => 'GENERAL INPUT',
        'supplier' => 'SUPPLIER',
        'patient' => 'PASIEN' 
    ]
];