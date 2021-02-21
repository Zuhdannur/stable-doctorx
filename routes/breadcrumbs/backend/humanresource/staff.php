<?php

Breadcrumbs::for('admin.humanresource.staff.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('humanresource::menus.staff.management'), route('admin.humanresource.staff.index'));
});

Breadcrumbs::for('admin.humanresource.staff.create', function ($trail) {
    $trail->parent('admin.humanresource.staff.index');
    $trail->push(trans('product::menus.staff.create'), route('admin.humanresource.staff.create'));
});

Breadcrumbs::for('admin.humanresource.staff.edit', function ($trail, $data) {
    $trail->parent('admin.humanresource.staff.index');
    $trail->push(__('humanresource::menus.staff.edit').' - '.$data->user->full_name, route('admin.humanresource.staff.edit', $data));
});