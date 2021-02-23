<?php

Breadcrumbs::for('admin.product.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('product::menus.product.management'), route('admin.product.index'));
});

Breadcrumbs::for('admin.product.create', function ($trail) {
    $trail->parent('admin.product.index');
    $trail->push(trans('product::menus.product.create'), route('admin.product.create'));
});

Breadcrumbs::for('admin.product.import', function ($trail) {
    $trail->parent('admin.product.index');
    $trail->push('Import Data Product', route('admin.product.import'));
});

Breadcrumbs::for('admin.product.edit', function ($trail, $id) {
    $trail->parent('admin.product.index');
    $trail->push(__('product::menus.product.edit'), route('admin.product.edit', $id));
});

Breadcrumbs::for('admin.product.productpackage.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Paket Obat', route('admin.product.productpackage.index'));
});

Breadcrumbs::for('admin.product.productpackage.edit', function ($trail, $id) {
    $trail->parent('admin.product.index');
    $trail->push('Edit Paket', route('admin.product.productpackage.edit', $id));
});

Breadcrumbs::for('admin.product.productpackage.create', function ($trail) {
    $trail->parent('admin.product.productpackage.index');
    $trail->push('Tambah Paket', route('admin.product.productpackage.create'));
});

Breadcrumbs::for('admin.productpackage.edit', function ($trail, $id) {
    $trail->parent('admin.product.productpackage.index');
    $trail->push('Ubah Paket Obat', route('admin.product.productpackage.edit', $id));
});

Breadcrumbs::for('admin.product.statistics', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Statistik Produk', route('admin.product.statistics'));
});

Breadcrumbs::for('admin.product.opname', function ($trail, $product) {
    $trail->parent('admin.product.index');
    $trail->push('Buat Stock Opname', route('admin.product.opname', $product));
});