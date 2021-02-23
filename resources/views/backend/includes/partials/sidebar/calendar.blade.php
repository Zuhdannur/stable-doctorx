<li class="{{ active_class(Active::checkUriPattern('admin/calendar*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/calendar*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-calendar"></i><span class="sidebar-mini-hide">Kalender</span></a>
    <ul>
        {{-- <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/patient/calendar')) }}" href="{{ route('admin.patient.calendar') }}">Jadwal Pasien</a>
        </li> --}}
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/calendar/jadwal/appointment')) }}" href="{{ route('admin.calendar.jadwal-appointment') }}">Jadwal Konsultasi Pasien</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/calendar/jadwal/treatment')) }}" href="{{ route('admin.calendar.jadwal-treatment') }}">Jadwal Treatment Pasien</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/calendar/jadwal/next')) }}" href="{{ route('admin.calendar.next-schedule') }}">Jadwal Pasien Lanjutan</a>
        </li>

        {{-- booking sidebar --}}
        @include('backend.includes.partials.sidebar.booking')
        {{-- end of booking sidebar --}}
    </ul>
</li>