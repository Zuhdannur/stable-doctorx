<li class="{{ active_class(Active::checkUriPattern('admin/accounting*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/accounting*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-wallet"></i><span class="sidebar-mini-hide">Accounting</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/accounting/cash*')) }}" href="{{ route('admin.accounting.cash') }}">Kas dan Bank</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/accounting/purchase*')) }}" href="{{ route('admin.accounting.purchase') }}">Pembelian</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/accounting/biaya*')) }}" href="{{ route('admin.accounting.biaya') }}">Biaya</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/accounting/log*')) }}" href="{{ route('admin.accounting.log.show') }}">Log activity</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/accounting/account*')) }}" href="{{ route('admin.accounting.account') }}">Daftar Akun Keuangan</a>
        </li> <li class="{{ active_class(Active::checkUriPattern('admin/accounting/reports*'), 'open') }}">
            <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/product/servicecategory*')) }}" data-toggle="nav-submenu" href="#">Laporan Keuangan</a>
            <ul>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/accounting/reports/neraca*')) }}" href="{{ route('admin.accounting.reports.neraca') }}">Neraca</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/accounting/reports/lost_profit*')) }}" href="{{ route('admin.accounting.reports.lost_profit') }}">Laba Rugi</a>
                </li>
            </ul>
        </li>
       
        <li class="{{ active_class(Active::checkUriPattern('admin/accounting/settings*'), 'open') }}">
            <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/accounting/settings*')) }}" data-toggle="nav-submenu" href="#">Setting</a>
            <ul>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/accounting/settings/tax*')) }}" href="{{ route('admin.accounting.settings.tax') }}">Kelola Pajak</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/accounting/settings/categories*')) }}" href="{{ route('admin.accounting.settings.categories') }}">Data Kategori Akun</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/accounting/settings/ms_categories*')) }}" href="{{ route('admin.accounting.settings.ms_categories') }}">Master  Kategori Akun</a>
                </li>
            </ul>
        </li>
    </ul>
</li>