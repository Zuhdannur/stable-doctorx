<?php

Breadcrumbs::for('admin.product.servicecategory.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('product::menus.servicecategory.management'), route('admin.product.servicecategory.index'));
});

Breadcrumbs::for('admin.product.servicecategory.create', function ($trail) {
    $trail->parent('admin.product.servicecategory.index');
    $trail->push(trans('product::menus.servicecategory.create'), route('admin.product.servicecategory.create'));
});

Breadcrumbs::for('admin.product.servicecategory.edit', function ($trail, $id) {
    $trail->parent('admin.product.servicecategory.index');
    $trail->push(__('product::menus.servicecategory.edit'), route('admin.product.servicecategory.edit', $id));
});

Breadcrumbs::for('admin.product.service.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('product::menus.service.management'), route('admin.product.service.index'));
});

Breadcrumbs::for('admin.product.service.create', function ($trail) {
    $trail->parent('admin.product.index');
    $trail->push(trans('product::menus.service.create'), route('admin.product.service.create'));
});

Breadcrumbs::for('admin.product.service.edit', function ($trail, $id) {
    $trail->parent('admin.product.index');
    $trail->push(__('product::menus.service.edit'), route('admin.product.service.edit', $id));
});

Breadcrumbs::for('admin.product.services-packages.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(__('product::menus.services-packages.management'), route('admin.product.services-packages.index'));
});

Breadcrumbs::for('admin.product.services-packages.create', function ($trail) {
    $trail->parent('admin.product.services-packages.index');
    $trail->push(__('product::menus.services-packages.create'), route('admin.product.services-packages.create'));
});

Breadcrumbs::for('admin.product.services-packages.show', function ($trail, $ServicesPackage) {
    $trail->parent('admin.product.services-packages.index');
    $trail->push(__('product::menus.services-packages.show'), route('admin.product.services-packages.show', $ServicesPackage));
});

Breadcrumbs::for('admin.product.services-packages.edit', function ($trail, $ServicesPackage) {
    $trail->parent('admin.product.services-packages.index');
    $trail->push(__('product::menus.services-packages.edit'), route('admin.product.services-packages.edit', $ServicesPackage));
});