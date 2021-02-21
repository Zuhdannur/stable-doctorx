<?php

Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->push(__('strings.backend.dashboard.title'), route('admin.dashboard'));
});

// Breadcrumbs::for('admin.statistik', function ($trail) {
//     $trail->push('Statistik', route('admin.statistik'));
// });

Breadcrumbs::for('admin.statistik.traffict', function ($trail) {
    $trail->push('Traffict', route('admin.statistik.traffict'));
});
Breadcrumbs::for('admin.statistik.revenue', function ($trail) {
    $trail->push('Revenue Stream', route('admin.statistik.revenue'));
});
Breadcrumbs::for('admin.statistik.demografi', function ($trail) {
    $trail->push('Analisa Demografi', route('admin.statistik.demografi'));
});
Breadcrumbs::for('admin.statistik.marketing', function ($trail) {
    $trail->push('Marketing Activity', route('admin.statistik.marketing'));
});
Breadcrumbs::for('admin.statistik.membership', function ($trail) {
    $trail->push('Membership', route('admin.statistik.membership'));
});

require __DIR__.'/auth.php';
require __DIR__.'/log-viewer.php';

require __DIR__.'/setting.php';
require __DIR__.'/product.php';
require __DIR__.'/room.php';
require __DIR__.'/patient.php';
require __DIR__.'/humanresource.php';
require __DIR__.'/billing.php';
require __DIR__.'/accounting.php';
require __DIR__.'/masterdata.php';