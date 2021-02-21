<?php

Breadcrumbs::register('admin.crm.point.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::labels.point.radeem.main'), route('admin.crm.point.index'));
});

Breadcrumbs::register('admin.crm.point.create', function ($trail) {
    $trail->parent('admin.crm.point.index');
    $trail->push(trans('crm::labels.point.radeem.create'), route('admin.crm.point.create'));
});

Breadcrumbs::register('admin.crm.point.edit', function ($trail, $data) {
    $trail->parent('admin.crm.point.index');
    $trail->push(trans('crm::labels.point.radeem.edit'), route('admin.crm.point.edit', $data));
});

Breadcrumbs::register('admin.crm.point.obat.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::labels.point.obat.main'), route('admin.crm.point.obat.index'));
});

Breadcrumbs::register('admin.crm.point.obat.edit', function ($trail, $product) {
    $trail->parent('admin.crm.point.obat.index');
    $trail->push(trans('crm::labels.point.obat.edit'), route('admin.crm.point.obat.edit', $product));
});

Breadcrumbs::register('admin.crm.point.service.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::labels.point.service.main'), route('admin.crm.point.service.index'));
});

Breadcrumbs::register('admin.crm.point.service.edit', function ($trail, $service) {
    $trail->parent('admin.crm.point.service.index');
    $trail->push(trans('crm::labels.point.service.edit'), route('admin.crm.point.service.edit', $service));
});
