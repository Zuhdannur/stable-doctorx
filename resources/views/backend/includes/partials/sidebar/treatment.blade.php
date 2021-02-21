<li class="{{ active_class(Active::checkUriPattern('admin/patient/treatment*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/patient/treatment*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-shuffle"></i><span class="sidebar-mini-hide">Treatment</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/treatment/*/*/create')) }}" href="{{ route('admin.patient.treatment.create', [0,0]) }}">Daftar Treatment</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/treatment/index')) }}" href="{{ route('admin.patient.treatment.index') }}">Data Treatment</a>
        </li>
    </ul>
</li>
