<li class="{{ active_class(Active::checkUriPattern('admin/patient*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/patient/patient*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-si si-user-follow"></i><span class="sidebar-mini-hide">Pasien</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/index')) }}" href="{{ route('admin.patient.index') }}">Data Pasien</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/beforeafter')) }}" href="{{ route('admin.patient.beforeafter') }}">Foto Before/After</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/birthday')) }}" href="{{ route('admin.patient.birthday') }}">Ulang Tahun Pasien</a>
        </li>

        @include('backend.includes.partials.sidebar.diagnose')
    </ul>
</li>