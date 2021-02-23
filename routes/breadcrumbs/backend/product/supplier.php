<?php

Breadcrumbs::for('admin.product.supplier', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('product::menus.supplier.management'), route('admin.product.supplier'));
});

Breadcrumbs::for('admin.product.supplier.create', function ($trail) {
    $trail->parent('admin.product.supplier');
    $trail->push(trans('product::menus.supplier.create'), route('admin.product.supplier.create'));
});

Breadcrumbs::for('admin.product.supplier.show', function ($trail, $supplier) {
    $trail->parent('admin.product.supplier');
    $trail->push(trans('product::labels.supplier.show'), route('admin.product.supplier.show', $supplier));
});

Breadcrumbs::for('admin.product.supplier.edit', function ($trail, $supplier) {
    $trail->parent('admin.product.supplier');
    $trail->push(trans('product::labels.supplier.edit'), route('admin.product.supplier.edit', $supplier));
});