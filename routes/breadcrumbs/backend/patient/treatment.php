<?php

Breadcrumbs::for('admin.patient.treatment.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('patient::menus.treatment.management'), route('admin.patient.treatment.index'));
});

Breadcrumbs::for('admin.patient.treatment.create', function ($trail, $id, $qId) {
    $trail->parent('admin.patient.treatment.index');
    $trail->push(trans('patient::menus.treatment.create'), route('admin.patient.treatment.create', [$id, $qId]));
});

Breadcrumbs::for('admin.patient.treatment.show', function ($trail, $id) {
    $trail->parent('admin.patient.treatment.index');
    $trail->push(__('patient::menus.treatment.show'), route('admin.patient.treatment.show', $id));
});