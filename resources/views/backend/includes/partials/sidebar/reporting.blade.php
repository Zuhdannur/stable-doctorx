<li class="{{ active_class(Active::checkUriPattern('admin/reporting*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/reporting*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-book-open"></i><span class="sidebar-mini-hide">Laporan</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/reporting/incentive*')) }}" href="{{ route('admin.reporting.incentive') }}">Laporan Komisi</a>
        </li>
        {{-- <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/reporting/nextappointment*')) }}" href="{{ route('admin.reporting.nextappointment') }}">Laporan Jadwal Konsultasi Lanjutan</a>
        </li> --}}
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/reporting/reportingpatient*')) }}" href="{{ route('admin.reporting.reportingpatient') }}">Laporan Data Pasien</a>
        </li>
    </ul>
</li>