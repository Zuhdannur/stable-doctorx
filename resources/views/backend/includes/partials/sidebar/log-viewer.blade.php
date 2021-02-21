<li class="{{ active_class(Active::checkUriPattern('admin/log-viewer*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/log-viewer*')) }}" data-toggle="nav-submenu" href="#"><i class="si si-puzzle"></i><span class="sidebar-mini-hide">@lang('menus.backend.log-viewer.main')</span></a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/log-viewer')) }}" href="{{ route('log-viewer::dashboard') }}">@lang('menus.backend.log-viewer.dashboard')</a>
        </li>
        
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/log-viewer/logs*')) }}" href="{{ route('log-viewer::logs.list') }}">@lang('menus.backend.log-viewer.logs')</a>
        </li>
    </ul>
</li>