<?php

Breadcrumbs::for('admin.patient.appointment.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('patient::menus.appointment.management'), route('admin.patient.appointment.index'));
});

Breadcrumbs::for('admin.patient.appointment.create', function ($trail, $id, $qId) {
    $trail->parent('admin.patient.appointment.index');
    $trail->push(trans('patient::menus.appointment.create'), route('admin.patient.appointment.create', [$id, $qId]));
});

Breadcrumbs::for('admin.patient.appointment.getFormEdit', function ($trail, $id) {
    $trail->parent('admin.patient.appointment.index');
    $trail->push(trans('patient::menus.appointment.edit'), route('admin.patient.appointment.getFormEdit', $id));
});

Breadcrumbs::for('admin.patient.appointment.show', function ($trail, $id) {
    $trail->parent('admin.patient.appointment.index');
    $trail->push(__('patient::menus.appointment.show'), route('admin.patient.appointment.show', $id));
});