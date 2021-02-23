<?php

Breadcrumbs::register('admin.crm.birthday.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::menus.birthday'), route('admin.crm.birthday.index'));
});

Breadcrumbs::register('admin.crm.birthday.editNoWA', function ($trail, $patient) {
    $trail->parent('admin.crm.birthday.index');
    $trail->push(trans('crm::labels.birthday.edit'), route('admin.crm.birthday.editNoWA', $patient));
});
