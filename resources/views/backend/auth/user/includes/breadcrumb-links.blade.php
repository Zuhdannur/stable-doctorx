<div class="btn-group" role="group" aria-label="Group Dropdown">
    <button type="button" class="btn btn-outline-primary dropdown-toggle" id="breadcrumb-dropdown-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('menus.backend.access.users.main')</button>
    <div class="dropdown-menu" aria-labelledby="toolbarDrop" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
        <a class="dropdown-item" href="{{ route('admin.auth.user.index') }}">
            <i class="fa fa-fw fa-bell mr-5"></i>@lang('menus.backend.access.users.all')
        </a>
        <a class="dropdown-item" href="{{ route('admin.auth.user.create') }}">
            <i class="fa fa-fw fa-envelope-o mr-5"></i>@lang('menus.backend.access.users.create')
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('admin.auth.user.deactivated') }}">
            <i class="fa fa-fw fa-pencil mr-5"></i>@lang('menus.backend.access.users.deactivated')
        </a>
        <a class="dropdown-item" href="{{ route('admin.auth.user.deleted') }}">
            <i class="fa fa-fw fa-pencil mr-5"></i>@lang('menus.backend.access.users.deleted')
        </a>
    </div>
</div>