<?php

Breadcrumbs::for('admin.patient.prescription.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('patient::menus.prescription.management'), route('admin.patient.prescription.index'));
});

Breadcrumbs::for('admin.patient.prescription.create', function ($trail, $id) {
    $trail->parent('admin.patient.prescription.index');
    $trail->push(trans('patient::menus.prescription.create'), route('admin.patient.prescription.create', $id));
});

Breadcrumbs::for('admin.patient.prescription.show', function ($trail, $id) {
    $trail->parent('admin.patient.prescription.index');
    $trail->push(__('patient::menus.prescription.show'), route('admin.patient.prescription.show', $id));
});

Breadcrumbs::for('admin.patient.prescription.edit', function ($trail, $id) {
    $trail->parent('admin.patient.prescription.index');
    $trail->push(__('patient::menus.prescription.edit'), route('admin.patient.prescription.edit', $id));
});