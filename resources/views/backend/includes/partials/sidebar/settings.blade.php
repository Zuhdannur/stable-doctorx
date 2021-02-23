<li class="{{ active_class(Active::checkUriPattern('admin/setting*'), 'open') }}">
    <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/setting*')) }}" data-toggle="nav-submenu" href="#">
        <i class="si si-badge"></i>
        <span class="sidebar-mini-hide">
            @lang('menus.backend.setting.title')
        </span>

        @if ($pending_approval > 0)
            <span class="badge badge-danger">{{ $pending_approval }}</span>
        @endif
    </a>
    <ul>
        <li>
            <a class="{{ active_class(Active::checkUriPattern('admin/setting/general*')) }}" href="{{ route('admin.setting.general.index') }}">
                <span class="sidebar-mini-hide">
                    @lang('labels.backend.setting.general.management')
                </span>

                @if ($pending_approval > 0)
                    <span class="badge badge-danger">{{ $pending_approval }}</span>
                @endif
            </a>
        </li>
        
    </ul>
</li>