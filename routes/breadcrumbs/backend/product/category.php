<?php

Breadcrumbs::for('admin.product.category.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('product::menus.category.management'), route('admin.product.category.index'));
});

Breadcrumbs::for('admin.product.category.create', function ($trail) {
    $trail->parent('admin.product.category.index');
    $trail->push(trans('product::menus.category.create'), route('admin.product.category.create'));
});

Breadcrumbs::for('admin.product.category.edit', function ($trail, $id) {
    $trail->parent('admin.product.category.index');
    $trail->push(__('product::menus.category.edit'), route('admin.product.category.edit', $id));
});