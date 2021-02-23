<?php

Breadcrumbs::register('admin.incentive.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Komisi', route('admin.incentive.index'));
});

Breadcrumbs::register('admin.incentive.create', function ($trail) {
    $trail->parent('admin.incentive.index');
    $trail->push('Tambah Insentif', route('admin.incentive.create'));
});

Breadcrumbs::register('admin.incentive.edit', function ($trail, $id) {
    $trail->parent('admin.incentive.index');
    $trail->push('Edit Insentif', route('admin.incentive.create', $id));
});

Breadcrumbs::register('admin.incentive.staff.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Komisi Staff', route('admin.incentive.staff.index'));
});

Breadcrumbs::register('admin.incentive.staff.create', function ($trail) {
    $trail->parent('admin.incentive.staff.index');
    $trail->push('Tambah Komisi Staff', route('admin.incentive.staff.create'));
});

Breadcrumbs::register('admin.reporting.incentive.staff', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Komisi', route('admin.reporting.incentive'));
});

Breadcrumbs::register('admin.reporting.incentive', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Komisi', route('admin.reporting.incentive.staff'));
});