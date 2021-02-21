<?php

Breadcrumbs::register('admin.crm.settings.membership', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::menus.settings.membership'), route('admin.crm.settings.membership'));
});

Breadcrumbs::register('admin.crm.settings.membership.create', function ($trail) {
    $trail->parent('admin.crm.settings.membership');
    $trail->push(trans('crm::labels.settings.membership.create'), route('admin.crm.settings.membership.create'));
});

Breadcrumbs::register('admin.crm.settings.membership.show', function ($trail, $membership) {
    $trail->parent('admin.crm.settings.membership');
    $trail->push(trans('crm::labels.settings.membership.edit'), route('admin.crm.settings.membership.show', $membership));
});

Breadcrumbs::register('admin.crm.settings.wa', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::menus.settings.whatsapp'), route('admin.crm.settings.wa'));
});