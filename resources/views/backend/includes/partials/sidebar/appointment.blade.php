<li class="{{ active_class(Active::checkUriPattern('admin/patient/appointment*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/patient/appointment*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-briefcase"></i><span class="sidebar-mini-hide">Konsultasi</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/appointment/create')) }}" href="{{ route('admin.patient.appointment.create', [0,0]) }}">Daftar Konsultasi</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/appointment/index')) }}" href="{{ route('admin.patient.appointment.index') }}">Data Konsultasi</a>
        </li>
    </ul>
</li>