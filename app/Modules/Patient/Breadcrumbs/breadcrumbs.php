<?php

// Breadcrumbs::register('admin.reporting.nextappointment', function ($trail) {
//     $trail->parent('admin.dashboard');
//     $trail->push('Jadwal Konsultasi Lanjutan', route('admin.reporting.nextappointment'));
// });
Breadcrumbs::register('admin.reporting.reportingpatient', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Laporan Data Pasien', route('admin.reporting.reportingpatient'));
});
Breadcrumbs::register('admin.patient.calendar', function ($trail) {
    $trail->push('Kalender Pasien', route('admin.patient.calendar'));
});