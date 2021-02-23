<?php

Breadcrumbs::register('admin.crm.membership.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::menus.membership'), route('admin.crm.membership.index'));
});

Breadcrumbs::register('admin.crm.membership.create', function ($trail) {
    $trail->parent('admin.crm.membership.index');
    $trail->push(trans('crm::labels.membership.create'), route('admin.crm.membership.create'));
});

Breadcrumbs::register('admin.crm.membership.edit', function ($trail, $membership) {
    $trail->parent('admin.crm.membership.index');
    $trail->push(trans('crm::labels.membership.edit'), route('admin.crm.membership.edit', $membership));
});

Breadcrumbs::register('admin.crm.membership.show', function ($trail, $membership) {
    $trail->parent('admin.crm.membership.index');
    $trail->push(trans('crm::labels.membership.show'), route('admin.crm.membership.show', $membership));
});
