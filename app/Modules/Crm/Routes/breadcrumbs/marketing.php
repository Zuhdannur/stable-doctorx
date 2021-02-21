<?php

Breadcrumbs::register('admin.crm.marketing.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::menus.marketing'), route('admin.crm.marketing.index'));
});

Breadcrumbs::register('admin.crm.marketing.create', function ($trail) {
    $trail->parent('admin.crm.marketing.index');
    $trail->push(trans('crm::labels.marketing.create'), route('admin.crm.marketing.create'));
});

Breadcrumbs::register('admin.crm.marketing.edit', function ($trail, $marketing) {
    $trail->parent('admin.crm.marketing.index');
    $trail->push(trans('crm::labels.marketing.edit'), route('admin.crm.marketing.edit', $marketing));
});
