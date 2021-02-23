<?php

Breadcrumbs::for('admin.room.group.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('room::menus.backend.group.management'), route('admin.room.group.index'));
});

Breadcrumbs::for('admin.room.group.create', function ($trail) {
    $trail->parent('admin.room.group.index');
    $trail->push(trans('room::menus.backend.group.create'), route('admin.room.group.create'));
});

Breadcrumbs::for('admin.room.group.edit', function ($trail, $id) {
    $trail->parent('admin.room.group.index');
    $trail->push(__('room::menus.backend.group.edit'), route('admin.room.group.edit', $id));
});