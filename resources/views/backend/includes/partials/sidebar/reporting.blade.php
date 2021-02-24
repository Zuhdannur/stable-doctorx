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
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/accounting/rekap-penjualan*')) }}" href="{{ route('rekap-penjualan.index') }}">Laporan Penjualan</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/accounting/rekap-produk*')) }}" href="{{ route('rekap-produk.index') }}">Laporan Produk</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/accounting/rekap-service*')) }}" href="{{ route('rekap-service.index') }}">Laporan Service</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('list-splitbill*')) }}" href="{{ route('list-splitbill.index') }}">Laporan Pembayaran Sebagian</a>
        </li>
    </ul>
</li>
