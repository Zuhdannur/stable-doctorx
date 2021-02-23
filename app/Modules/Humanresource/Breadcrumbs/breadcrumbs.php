<?php
//Department
Breadcrumbs::for('admin.humanresource.department.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Departemen', route('admin.humanresource.department.index'));
});

Breadcrumbs::for('admin.humanresource.department.create', function ($trail) {
    $trail->parent('admin.humanresource.department.index');
    $trail->push('Tambah Departemen', route('admin.humanresource.department.create'));
});

Breadcrumbs::register('admin.humanresource.department.edit', function ($trail, $id) {
    $trail->parent('admin.humanresource.department.index');
    $trail->push('Edit Departemen', route('admin.humanresource.department.edit', $id));
});


//Designation
Breadcrumbs::for('admin.humanresource.designation.index', function ($trail) {
    $trail->parent('admin.dashboard');
    $trail->push('Departemen', route('admin.humanresource.designation.index'));
});

Breadcrumbs::for('admin.humanresource.designation.create', function ($trail) {
    $trail->parent('admin.humanresource.designation.index');
    $trail->push('Tambah Departemen', route('admin.humanresource.designation.create'));
});

Breadcrumbs::register('admin.humanresource.designation.edit', function ($trail, $id) {
    $trail->parent('admin.humanresource.designation.index');
    $trail->push('Edit Departemen', route('admin.humanresource.designation.edit', $id));
});
