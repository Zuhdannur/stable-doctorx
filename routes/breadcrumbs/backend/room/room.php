<?php

Breadcrumbs::for('admin.room.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('room::menus.backend.room.management'), route('admin.room.index'));
});

Breadcrumbs::for('admin.room.create', function ($trail) {
    $trail->parent('admin.room.index');
    $trail->push(trans('room::menus.backend.room.create'), route('admin.room.create'));
});

Breadcrumbs::for('admin.room.edit', function ($trail, $id) {
    $trail->parent('admin.room.index');
    $trail->push(__('room::menus.backend.room.edit'), route('admin.room.edit', $id));
});