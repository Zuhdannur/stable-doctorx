<li class="{{ active_class(Active::checkUriPattern('admin/patient/prescription*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/patient/prescription*')) }}" data-toggle="nav-submenu" href="#">Resep & Diagnosa</a>
    <ul>
        <li class="d-none">
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/prescription/create*')) }}" href="{{ route('admin.patient.prescription.create', '0') }}">Input Resep & Diagnosa</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/prescription/index')) }}" href="{{ route('admin.patient.prescription.index') }}">Data Resep & Diagnosa</a>
        </li>
    </ul>
</li>