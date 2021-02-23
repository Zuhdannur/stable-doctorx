<?php

Breadcrumbs::register('admin.billing.show', function ($trail, $id) {
    $trail->parent('admin.billing.index');
    $trail->push(trans('billing::menus.billing.show'), route('admin.billing.show', $id));
});

Breadcrumbs::register('admin.billing.edit', function ($trail, $id) {
    $trail->parent('admin.billing.index');
    $trail->push('Edit Billing', route('admin.billing.edit', $id));
});