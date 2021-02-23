{{-- <li>
    <a class="{{ active_class(Active::checkUriPattern('admin/statistik')) }}" href="{{ route('admin.statistik') }}">
        <i class="si si-bar-chart"></i>
        <span class="sidebar-mini-hide">Statistik</span>
    </a>
</li> --}}
<li class="{{ active_class(Active::checkUriPattern('admin/statistik*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/statistik*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-chart"></i><span class="sidebar-mini-hide">Statistik</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/statistik/traffict')) }}" href="{{ route('admin.statistik.traffict') }}">Traffict</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/statistik/revenue')) }}" href="{{ route('admin.statistik.revenue') }}">Revenue Stream</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/statistik/demografi')) }}" href="{{ route('admin.statistik.demografi') }}">Analisa Demografi</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/statistik/marketing')) }}" href="{{ route('admin.statistik.marketing') }}">Marketing Activity</a>
        </li>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/statistik/membership')) }}" href="{{ route('admin.statistik.membership') }}">Membership</a>
        </li>
    </ul>
</li>