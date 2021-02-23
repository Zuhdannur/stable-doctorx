<?php

Breadcrumbs::for('admin.auth.ability.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('menus.backend.access.abilities.management'), route('admin.auth.ability.index'));
});

Breadcrumbs::for('admin.auth.ability.create', function ($trail) {
    $trail->parent('admin.auth.ability.index');
    $trail->push(__('menus.backend.access.abilities.create'), route('admin.auth.ability.create'));
});

Breadcrumbs::for('admin.auth.ability.edit', function ($trail, $id) {
    $trail->parent('admin.auth.ability.index');
    $trail->push(__('menus.backend.access.abilities.edit'), route('admin.auth.ability.edit', $id));
});
