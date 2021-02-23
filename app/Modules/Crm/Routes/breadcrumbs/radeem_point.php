<?php

Breadcrumbs::register('admin.crm.radeem.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('crm::labels.radeem.main'), route('admin.crm.radeem.index'));
});