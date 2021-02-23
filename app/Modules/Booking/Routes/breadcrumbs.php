<?php

Breadcrumbs::register('admin.booking.room', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('booking::menus.booking.room'), route('admin.booking.room'));
});

Breadcrumbs::register('admin.booking.therapist', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('booking::menus.booking.therapist'), route('admin.booking.therapist'));
});

Breadcrumbs::register('admin.booking.docter', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('booking::menus.booking.docter'), route('admin.booking.docter'));
});

Breadcrumbs::register('admin.calendar.jadwal-appointment', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('booking::menus.calendar.jadwal-appointment'), route('admin.calendar.jadwal-appointment'));
});

Breadcrumbs::register('admin.calendar.jadwal-treatment', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push(trans('booking::menus.calendar.jadwal-treatment'), route('admin.calendar.jadwal-treatment'));
});

Breadcrumbs::register('admin.calendar.next-schedule', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Jadwal Konsultasi Lanjutan', route('admin.calendar.next-schedule'));
});