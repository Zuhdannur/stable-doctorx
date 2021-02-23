<?php

Breadcrumbs::register('admin.departments.index', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.dashboard');
    $breadcrumbs->push(trans('menus.backend.departments.management'), route('admin.departments.index'));
});

Breadcrumbs::register('admin.departments.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.departments.index');
    $breadcrumbs->push(trans('menus.backend.departments.create'), route('admin.departments.create'));
});

Breadcrumbs::register('admin.departments.edit', function ($breadcrumbs, $id) {
    $breadcrumbs->parent('admin.departments.index');
    $breadcrumbs->push(trans('menus.backend.departments.edit'), route('admin.departments.edit', $id));
});
