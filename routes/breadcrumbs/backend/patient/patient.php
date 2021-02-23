<?php

Breadcrumbs::for('admin.patient.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('patient::menus.patient.management'), route('admin.patient.index'));
});

Breadcrumbs::for('admin.patient.create', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Create Pasien', route('admin.patient.create'));
});

Breadcrumbs::for('admin.patient.show', function ($trail, $id) {
    $trail->parent('admin.patient.index');
    $trail->push(__('patient::menus.patient.show'), route('admin.patient.show', $id));
});

Breadcrumbs::register('admin.patient.edit', function ($trail, $id) {
    $trail->parent('admin.patient.index');
    $trail->push('Edit Data Pasien', route('admin.patient.edit', $id));
});
Breadcrumbs::for('admin.patient.queues', function ($trail) {
    $trail->push('Antrian', route('admin.patient.queues'));
});

Breadcrumbs::for('admin.patient.has-queues', function ($trail) {
    $trail->push('Antrian', route('admin.patient.has-queues'));
});

Breadcrumbs::for('admin.patient.beforeafter', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Before/After', route('admin.patient.beforeafter'));
});

Breadcrumbs::for('admin.patient.birthday', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Ulang Tahun Pasien', route('admin.patient.birthday'));
});