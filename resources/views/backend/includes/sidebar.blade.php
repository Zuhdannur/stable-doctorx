<nav id="sidebar">
    <!-- Sidebar Scroll Container -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="content-header content-header-fullrow px-15">
                <!-- Mini Mode -->
                <div class="content-header-section sidebar-mini-visible-b">
                    <!-- Logo -->
                    <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                                            <span class="text-dual-primary-dark">D</span><span
                            class="text-primary">M</span>
                    </span>
                    <!-- END Logo -->
                </div>
                <!-- END Mini Mode -->

                <!-- Normal Mode -->
                <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r"
                            data-toggle="layout" data-action="sidebar_close">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                    <!-- END Close Sidebar -->

                    <!-- Logo -->
                    <div class="content-header-item">
                        <a class="link-effect font-w700" href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-medkit text-success"></i>
                            <span class="font-size-xl text-primary">{{ setting()->get('app_name') }}</span>
                        </a> <br>
                        <span class="badge badge-info">{{ Auth()->user()->klinik->nama_klinik.' - '.Auth()->user()->klinik->status }} </span>
                    </div>
                    <!-- END Logo -->
                </div>
                <!-- END Normal Mode -->
            </div>
            <!-- END Side Header -->

            <!-- Side User -->
            <div class="content-side content-side-full content-side-user px-10 align-parent d-none">
                <!-- Visible only in mini mode -->
                <div class="sidebar-mini-visible-b align-v animated fadeIn">
                    <img class="img-avatar img-avatar2" src="{{ $logged_in_user->picture }}" alt="">
                </div>
                <!-- END Visible only in mini mode -->

                <!-- Visible only in normal mode -->
                <div class="sidebar-mini-hidden-b text-center">
                    <a class="img-link" href="javascript:void(0)">
                        <img class="img-avatar" src="{{ $logged_in_user->picture }}" alt="">
                    </a>
                    <ul class="list-inline mt-10">
                        <li class="list-inline-item">
                            <a class="link-effect text-dual-primary-dark font-size-xs font-w600"
                               href="javascript:void(0)">{{ $logged_in_user->name }}</a>
                        </li>
                        <li class="list-inline-item">
                            <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->
                            <a class="link-effect text-dual-primary-dark" data-toggle="layout"
                               data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
                                <i class="si si-drop"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a class="link-effect text-dual-primary-dark" href="{{ route('frontend.auth.logout') }}"
                               title="@lang('navs.general.logout')">
                                <i class="si si-logout"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- END Visible only in normal mode -->
            </div>
            <!-- END Side User -->
        @php
                $akses = array();
                foreach(Auth()->user()->roles as $item) {
                    $modulAccess = \App\ModelAccess::where('id_user',$item->id)->get();
                    foreach ($modulAccess as $modul) {
                        array_push($akses,$modul->id_modul);
                    }
                }
                array_unique($akses);

        @endphp
{{--            foreach ($modulAccess as $index => $item){--}}
{{--            $akses[$index] = $item->id_modul;--}}
{{--            }--}}
        <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    {{-- Dashboard sidebar --}}
                    @foreach(\App\Modul::orderBy('id_modul','asc')->get() as $item)
                        @if(empty($item->url_modul) && empty(@$item->icon) && in_array($item->id_modul,$akses))
                            <li class="nav-main-heading"><span class="sidebar-mini-visible">UM</span><span
                                    class="sidebar-mini-hidden">{{ @$item->nama_modul }}</span></li>
                            @foreach(\App\Modul::where('parent_id',@$item->id_modul)->get() as $sub)
                                @if(in_array($sub->id_modul,$akses))
                                @if(strpos(@$sub->url_modul,'*') !== false)
                                    <li class="{{ active_class(Active::checkUriPattern(@$sub->url_modul), 'open') }}">
                                        <a class="nav-submenu {{ active_class(Active::checkUriPattern(@$sub->url_modul)) }}"
                                           data-toggle="nav-submenu" href="#"><i class="{{ @$sub->icon }}"></i><span
                                                class="sidebar-mini-hide">{{ @$sub->nama_modul }}</span></a>
                                        <ul>
                                            @foreach(\App\Modul::where('parent_id',@$sub->id_modul)->get() as $sub_item)
                                                @if(in_array($sub_item->id_modul,$akses))
                                                @if(strpos(@$sub_item->url_modul,'*') !== false)
                                                    <li class="{{ active_class(Active::checkUriPattern(@$sub_item->url_modul), 'open') }}">
                                                        <a class="nav-submenu {{ active_class(Active::checkUriPattern(@$sub_item->url_modul)) }}"
                                                           data-toggle="nav-submenu" href="#"><i
                                                                class="{{ @$sub_item->icon }}"></i><span
                                                                class="sidebar-mini-hide">{{ @$sub_item->nama_modul }}</span></a>
                                                        <ul>
                                                    @foreach(\App\Modul::where('parent_id',@$sub_item->id_modul)->get() as $sub_sub_item)
                                                        @if(in_array($sub_sub_item->id_modul,$akses))
                                                        @if(strpos(@$sub_sub_item->url_modul,'*') !== false)
                                                            <li class="{{ active_class(Active::checkUriPattern(@$sub_sub_item->url_modul), 'open') }}">
                                                                <a class="nav-submenu {{ active_class(Active::checkUriPattern(@$sub_sub_item->url_modul)) }}"
                                                                   data-toggle="nav-submenu" href="#"><i
                                                                        class="{{ @$sub_sub_item->icon }}"></i><span
                                                                        class="sidebar-mini-hide">{{ @$sub_sub_item->nama_modul }}</span></a>
                                                                <ul>
                                                                @foreach(\App\Modul::where('parent_id',@$sub_sub_item->id_modul)->get() as $sub_sub_sub_item)
                                                                        @if(in_array($sub_sub_sub_item->id_modul,$akses))
                                                                        @if(strpos(@$sub_sub_sub_item->url_modul,'*') !== false)
                                                                            <li class="{{ active_class(Active::checkUriPattern(@$sub_sub_sub_item->url_modul), 'open') }}">
                                                                                <a class="nav-submenu {{ active_class(Active::checkUriPattern(@$sub_sub_sub_item->url_modul)) }}"
                                                                                   data-toggle="nav-submenu" href="#"><i
                                                                                        class="{{ @$sub_sub_sub_item->icon }}"></i><span
                                                                                        class="sidebar-mini-hide">{{ @$sub_sub_sub_item->nama_modul }}</span></a>
                                                                                <ul>
                                                                                    @foreach(\App\Modul::where('parent_id',@$sub_sub_sub_item->id_modul)->get() as $sub_sub_sub_sub_item)
                                                                                        @if(in_array($sub_sub_sub_sub_item->id_modul,$akses))
                                                                                        <li>
                                                                                            <a class="{{ active_class(Active::checkUriPattern($sub_sub_sub_sub_item->url_modul)) }}"
                                                                                               href="{{ url($sub_sub_sub_sub_item->url_modul) }}">{{ $sub_sub_sub_sub_item->nama_modul }}</a>
                                                                                        </li>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </ul>
                                                                            </li>
                                                                        @else
                                                                            <li>
                                                                                <a class="{{ active_class(Active::checkUriPattern($sub_sub_sub_item->url_modul)) }}"
                                                                                   href="{{ url($sub_sub_sub_item->url_modul) }}">{{ $sub_sub_sub_item->nama_modul }}</a>
                                                                            </li>
                                                                        @endif
                                                                        @endif
                                                                @endforeach
                                                                </ul>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a class="{{ active_class(Active::checkUriPattern($sub_sub_item->url_modul)) }}"
                                                                               href="{{ url($sub_sub_item->url_modul) }}">{{ $sub_sub_item->nama_modul }}</a>
                                                            </li>
                                                        @endif
                                                                @endif
                                                    @endforeach
                                                        </ul>
                                                    </li>
                                                @else
                                                      <li>
                                                          <a class="{{ active_class(Active::checkUriPattern($sub_item->url_modul)) }}"
                                                             href="{{ url($sub_item->url_modul) }}">{{ $sub_item->nama_modul }}</a>
                                                      </li>
                                                @endif
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li>
                                        <a class="{{ active_class(Active::checkUriPattern($sub->url_modul)) }}"
                                           href="{{ url($sub->url_modul) }}">
                                            <i class="si si-cup"></i>
                                            <span class="sidebar-mini-hide">{{ $sub->nama_modul }}</span>
                                        </a>
                                    </li>
                                @endif
                                @endif
                            @endforeach
                        @endif
                    @endforeach
{{--                    <li>--}}
{{--                        <a class="{{ active_class(Active::checkUriPattern('admin/dashboard')) }}"--}}
{{--                           href="{{ route('admin.dashboard') }}">--}}
{{--                            <i class="si si-cup"></i>--}}
{{--                            <span class="sidebar-mini-hide">@lang('menus.backend.sidebar.dashboard')</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    {{-- End Of Dashboard sidebar --}}

                    {{-- calendar sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.calendar')--}}
                    {{-- end of calendar sidebar --}}

                    {{-- statistics sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.statistics')--}}
                    {{-- end of statistics sidebar --}}
{{--                    <li class="nav-main-heading"><span class="sidebar-mini-visible">UM</span><span--}}
{{--                            class="sidebar-mini-hidden">@lang('menus.backend.sidebar.general')</span></li>--}}

                    {{-- patient sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.patient')--}}
                    {{-- end of patient sidebar --}}

                    {{-- appointment sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.appointment')--}}
                    {{-- end of appointment sidebar --}}

                    {{-- treatment sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.treatment')--}}
                    {{-- end of treatment sidebar --}}

                    {{-- billing sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.billing')--}}
                    {{-- end of billing sidebar --}}

                    {{-- crm sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.crm')--}}
                    {{-- end of crm sidebar --}}

{{--                    <li class="nav-main-heading"><span class="sidebar-mini-visible">SYS</span><span--}}
{{--                            class="sidebar-mini-hidden">@lang('menus.backend.sidebar.system')</span></li>--}}

                    {{-- master-data sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.master-data')--}}
                    {{-- end of master-data sidebar --}}

                    {{-- accounting sidebar --}}
{{--                    @if(!empty(""))--}}
{{--                    @include('backend.includes.partials.sidebar.accounting')--}}
{{--                    @endif--}}
                    {{-- end of accounting sidebar --}}

                    {{-- reporting sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.reporting')--}}
                    {{-- end of reporting sidebar --}}

                    {{-- humanresource sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.humanresource')--}}
                    {{-- end of humanresource sidebar --}}

                    {{-- incentive sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.incentive')--}}
                    {{-- end of incentive sidebar --}}

{{--                    <li class="{{ active_class(Active::checkUriPattern('admin/auth*'), 'open') }}">--}}
{{--                        <a class="nav-submenu {{ active_class(Active::checkUriPattern('admin/auth*')) }}"--}}
{{--                           data-toggle="nav-submenu" href="#">--}}
{{--                            <i class="si si-lock"></i>--}}
{{--                            <span class="sidebar-mini-hide">--}}
{{--                                    @lang('menus.backend.access.title')--}}
{{--                                </span>--}}

{{--                            @if ($pending_approval > 0)--}}
{{--                                <span class="badge badge-danger">{{ $pending_approval }}</span>--}}
{{--                            @endif--}}
{{--                        </a>--}}
{{--                        <ul>--}}
{{--                            <li>--}}
{{--                                <a class="{{ active_class(Active::checkUriPattern('admin/auth/user*')) }}"--}}
{{--                                   href="{{ route('admin.auth.user.index') }}">--}}
{{--                                        <span class="sidebar-mini-hide">--}}
{{--                                            @lang('labels.backend.access.users.management')--}}
{{--                                        </span>--}}
{{--                                    @if ($pending_approval > 0)--}}
{{--                                        <span class="badge badge-danger">{{ $pending_approval }}</span>--}}
{{--                                    @endif--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a class="{{ active_class(Active::checkUriPattern('admin/auth/role*')) }}"--}}
{{--                                   href="{{ route('admin.auth.role.index') }}">--}}
{{--                                        <span class="sidebar-mini-hide">--}}
{{--                                            @lang('labels.backend.access.roles.management')--}}
{{--                                        </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <a class="{{ active_class(Active::checkUriPattern('admin/auth/ability*')) }}"--}}
{{--                                   href="{{ route('admin.auth.ability.index') }}">--}}
{{--                                        <span class="sidebar-mini-hide">--}}
{{--                                            @lang('labels.backend.access.abilities.management')--}}
{{--                                        </span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </li>--}}

                    {{-- log-viewer sidebar --}}
                    {{-- @include('backend.includes.partials.sidebar.log-viewer') --}}
                    {{-- end of log-viewer sidebar --}}

                    {{-- settings sidebar --}}
{{--                    @include('backend.includes.partials.sidebar.settings')--}}
                    {{-- end of settings sidebar --}}

                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- Sidebar Content -->
    </div>
    <!-- END Sidebar Scroll Container -->
</nav>
