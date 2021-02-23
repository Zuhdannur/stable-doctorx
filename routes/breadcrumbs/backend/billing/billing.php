<?php

Breadcrumbs::for('admin.billing.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('billing::menus.billing.management'), route('admin.billing.index'));
});

Breadcrumbs::for('admin.billing.create', function ($trail, $patientId, $qId) {
    $trail->parent('admin.billing.index');
    $trail->push(trans('billing::menus.billing.create'), route('admin.billing.create', [$patientId, $qId]));
});