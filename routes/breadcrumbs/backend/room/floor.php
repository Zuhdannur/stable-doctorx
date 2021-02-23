<?php

Breadcrumbs::for('admin.room.floor.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('room::menus.backend.floor.management'), route('admin.room.floor.index'));
});

Breadcrumbs::for('admin.room.floor.create', function ($trail) {
    $trail->parent('admin.room.floor.index');
    $trail->push(trans('room::menus.backend.floor.create'), route('admin.room.floor.create'));
});

Breadcrumbs::for('admin.room.floor.edit', function ($trail, $id) {
    $trail->parent('admin.room.floor.index');
    $trail->push(__('room::menus.backend.floor.edit'), route('admin.room.floor.edit', $id));
});