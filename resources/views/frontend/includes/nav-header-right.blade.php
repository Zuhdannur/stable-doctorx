<div class="content-header-section">
    <div class="btn-group ml-5" role="group">
        <div class="btn-group" role="group">
            @auth
            <button type="button" class="btn btn-rounded btn-dual-secondary {{ active_class(Active::checkRoute('frontend.user.account')) }}" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ $logged_in_user->name }}<i class="fa fa-angle-down ml-5"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">
                @can('view backend')
                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                        <i class="si si-user mr-5"></i> @lang('navs.frontend.user.administration')
                    </a>
                    <div class="dropdown-divider"></div>
                @endcan

                <a href="{{ route('frontend.user.account') }}" class="dropdown-item {{ active_class(Active::checkRoute('frontend.user.account')) }}"><i class="fa fa-user-secret mr-5"></i> @lang('navs.frontend.user.account')</a>

                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('frontend.auth.logout') }}">
                    <i class="si si-logout mr-5"></i> @lang('navs.general.logout') </a>
            </div>
            @endauth
        </div>
    </div>

    <!-- Open Search Section -->
    <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="header_search_on">
        <i class="fa fa-search"></i>
    </button>
    <!-- END Open Search Section -->

    <!-- Toggle Sidebar -->
    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
        <i class="fa fa-navicon"></i>
    </button>
    <!-- END Toggle Sidebar -->
</div>