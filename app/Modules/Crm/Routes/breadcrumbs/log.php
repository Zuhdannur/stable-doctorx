<?php

/**
 * Log
 */
Breadcrumbs::for('admin.crm.log.show', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('log.show'), route('admin.crm.log.show'));
});