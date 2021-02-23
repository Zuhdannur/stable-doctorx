<?php

Breadcrumbs::for('admin.masterdata.diagnoseitem.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Data Diagnosa', route('admin.masterdata.diagnoseitem.index'));
});

Breadcrumbs::for('admin.masterdata.diagnoseitem.create', function ($trail) {
    $trail->parent('admin.masterdata.diagnoseitem.index');
    $trail->push('Tambah Diagnosa', route('admin.masterdata.diagnoseitem.create'));
});

Breadcrumbs::for('admin.masterdata.diagnoseitem.edit', function ($trail, $id) {
    $trail->parent('admin.masterdata.diagnoseitem.index');
    $trail->push('Ubah Diagnosa', route('admin.masterdata.diagnoseitem.edit', $id));
});