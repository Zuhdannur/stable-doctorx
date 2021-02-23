<li class="{{ active_class(Active::checkUriPattern('admin/incentive*'), 'open') }} d-none">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/incentive*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-calculator"></i><span class="sidebar-mini-hide">Komisi</span></a>
    <ul>
        <li class="{{ active_class(Active::checkUriPattern('admin/incentive*'), 'open') }}">
            <a class="{{ active_class(Active::checkUriPattern('admin/incentive/incentive*')) }}" href="{{ route('admin.incentive.index') }}">Grup Komisi</a>
        </li>
        <li class="{{ active_class(Active::checkUriPattern('admin/incentive*'), 'open') }}">
            <a class="{{ active_class(Active::checkUriPattern('admin/incentive/staff*')) }}" href="{{ route('admin.incentive.staff.index') }}">Komisi Staf</a>
        </li>
    </ul>
</li>