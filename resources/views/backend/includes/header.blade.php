<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="content-header-section">
            <!-- Toggle Sidebar -->
            <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="sidebar_toggle">
                <i class="fa fa-navicon"></i>
            </button>
            <!-- END Toggle Sidebar -->

            <!-- Open Search Section -->
            <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
            <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout" data-action="header_search_on">
                <i class="fa fa-search"></i>
            </button>
            <!-- END Open Search Section -->

            <a href="{{ route('admin.patient.queues') }}" title="Display Antrian Pendaftar" target="_blank" class="btn btn-circle btn-dual-secondary">
                <i class="fa fa-indent"></i>
            </a>

            <a href="{{ route('admin.patient.has-queues') }}" title="Display Antrian" target="_blank" class="btn btn-circle btn-dual-secondary">
                <i class="fa fa-outdent"></i>
            </a>

            @if(config('locale.status') && count(config('locale.languages')) > 1)
            <div class="btn-group" role="group" aria-label="Group Language">
                <button type="button" class="btn btn-sm btn-rounded btn-noborder btn-outline-secondary dropdown-toggle" id="toolbarLanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">@lang('menus.language-picker.language') ({{ strtoupper(app()->getLocale()) }})</button>
                @include('includes.partials.lang')
            </div>
            @endif

        </div>
        <!-- END Left Section -->

        <!-- Right Section -->
        <div class="content-header-section">
            <!-- User Dropdown -->
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ $logged_in_user->name }}<i class="fa fa-angle-down ml-5"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right min-width-150" aria-labelledby="page-header-user-dropdown">
                    <a class="dropdown-item" href="{{ route('frontend.user.account') }}">
                        <i class="si si-user mr-5"></i> @lang('navs.frontend.user.account')
                    </a>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="{{ route('frontend.index') }}">
                        <i class="si si-wrench mr-5"></i> @lang('navs.general.home')
                    </a>

                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('frontend.auth.logout') }}">
                        <i class="si si-logout mr-5"></i> @lang('navs.general.logout')
                    </a>
                </div>
            </div>
            <!-- END User Dropdown -->

            <!-- Toggle Side Overlay -->
            <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
            <button type="button" class="btn btn-rounded btn-dual-secondary" data-toggle="layout" data-action="side_overlay_toggle">
               <span class="badge badge-danger mr-5 animated swing infinite">
                    <i class="fa fa-exclamation-triangle"></i>
                </span>
                <i class="fa fa-flag"></i>
            </button>
            <!-- END Toggle Side Overlay -->
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->

    <!-- Header Search -->
    <div id="page-header-search" class="overlay-header">
        <div class="content-header content-header-fullrow">
            <form action="be_pages_generic_search.html" method="post">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <!-- Close Search Section -->
                        <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                        <button type="button" class="btn btn-secondary" data-toggle="layout" data-action="header_search_off">
                            <i class="fa fa-times"></i>
                        </button>
                        <!-- END Close Search Section -->
                    </div>
                    <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- END Header Search -->

    <!-- Header Loader -->
    <!-- Please check out the Activity page under Elements category to see examples of showing/hiding it -->
    <div id="page-header-loader" class="overlay-header bg-primary">
        <div class="content-header content-header-fullrow text-center">
            <div class="content-header-item">
                <i class="fa fa-sun-o fa-spin text-white"></i>
            </div>
        </div>
    </div>
    <!-- END Header Loader -->
</header>