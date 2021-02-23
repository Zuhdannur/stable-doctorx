<li class="{{ active_class(Active::checkUriPattern('admin/crm*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/crm*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-tag"></i><span class="sidebar-mini-hide">CRM</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/crm/membership*')) }}" href="{{ route('admin.crm.membership.index') }}">Data Membership</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/crm/marketing*')) }}" href="{{ route('admin.crm.marketing.index') }}">Marketing Activity</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/crm/radeem*')) }}" href="{{ route('admin.crm.radeem.index') }}">Radeem Point</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/crm/birthday*')) }}" href="{{ route('admin.crm.birthday.index') }}">Ulang Tahun Membership</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/crm/log*')) }}" href="{{ route('admin.crm.log.show') }}">Log Activity</a>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/crm/point*'), 'open') }}">
            <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/crm/point*')) }}" data-toggle="nav-submenu" href="#">Kelola Point</a>
            <ul>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/crm/point/radeem*')) }}" href="{{ route('admin.crm.point.index') }}">Master Radeem Point</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/crm/point/obat*')) }}" href="{{ route('admin.crm.point.obat.index') }}">Point Pembelian Obat</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/crm/point/service*')) }}" href="{{ route('admin.crm.point.service.index') }}">Point Service</a>
                </li>
            </ul>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/crm/settings*'), 'open') }}">
            <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/crm/settings*')) }}" data-toggle="nav-submenu" href="#">Setting</a>
            <ul>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/crm/settings/membership*')) }}" href="{{ route('admin.crm.settings.membership') }}">Master Membership</a>
                </li>
                <li>
                    <a class="{{ active_class(Active::checkUriPattern('admin/crm/settings/wa*')) }}" href="{{ route('admin.crm.settings.wa') }}">Vendor Whatssapp</a>
                </li>
            </ul>
        </li>
    </ul>
</li>