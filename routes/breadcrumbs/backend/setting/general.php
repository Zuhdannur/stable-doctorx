<?php

Breadcrumbs::for('admin.setting.general.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('fees::menus.backend.fees.feetype.management'), route('admin.setting.general.index'));
});
