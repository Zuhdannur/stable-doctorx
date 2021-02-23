<li class="{{ active_class(Active::checkUriPattern('admin/humanresource*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/humanresource*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-users"></i><span class="sidebar-mini-hide">Manajemen SDM</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/humanresource/department*')) }}" href="{{ route('admin.humanresource.department.index') }}">Departemen</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/humanresource/designation*')) }}" href="{{ route('admin.humanresource.designation.index') }}">Jabatan</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/humanresource/staff*')) }}" href="{{ route('admin.humanresource.staff.index') }}">Staff</a>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/incentive*'), 'open') }}">
            <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/incentive*')) }}" data-toggle="nav-submenu" href="#">Komisi</a>
            <ul>
                <li class="{{ active_class(Active::checkUriPattern('admin/incentive*'), 'open') }}">
                    <a class="{{ active_class(Active::checkUriPattern('admin/incentive/incentive*')) }}" href="{{ route('admin.incentive.index') }}">Grup Komisi</a>
                </li>
                <li class="{{ active_class(Active::checkUriPattern('admin/incentive*'), 'open') }}">
                    <a class="{{ active_class(Active::checkUriPattern('admin/incentive/staff*')) }}" href="{{ route('admin.incentive.staff.index') }}">Komisi Staf</a>
                </li>
            </ul>
        </li>
    </ul>
</li>