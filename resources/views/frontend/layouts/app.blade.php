<!doctype html>
<!--[if lte IE 9]>     <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <title>@yield('title', app_name())</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="description" content="@yield('title', app_name())">
        <meta name="author" content="@yield('title', app_name())">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="@yield('title', app_name())">
        <meta property="og:site_name" content="@yield('title', app_name())">
        <meta property="og:description" content="@yield('title', app_name())">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset(setting()->get('favicon')) }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset(setting()->get('favicon')) }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(setting()->get('favicon')) }}">
        <!-- END Icons -->

        <!-- Fonts and Styles -->
        @yield('css_before')
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">

        <link rel="stylesheet" id="css-theme" href="{{ mix('/css/all.css') }}">
        <link rel="stylesheet" id="css-main" href="{{ mix('/css/codebase.css') }}">
        <style>
            .switch-input {
                display: none;
            }

            .switch-label {
                position: relative;
                display: inline-block;
                min-width: 112px;
                cursor: pointer;
                font-weight: 500;
                text-align: left;
                /* margin: 16px; */
                font-size: 13px;
                padding: 7px 0 7px 44px;
            }

            .switch-label:before,
            .switch-label:after {
                content: "";
                position: absolute;
                margin: 0;
                outline: 0;
                top: 50%;
                -ms-transform: translate(0, -50%);
                -webkit-transform: translate(0, -50%);
                transform: translate(0, -50%);
                -webkit-transition: all 0.3s ease;
                transition: all 0.3s ease;
            }

            .switch-label:before {
                left: 1px;
                width: 34px;
                height: 14px;
                background-color: #9E9E9E;
                border-radius: 8px;
            }

            .switch-label:after {
                left: 0;
                width: 20px;
                height: 20px;
                background-color: #FAFAFA;
                border-radius: 50%;
                box-shadow: 0 3px 1px -2px rgba(0, 0, 0, 0.14), 0 2px 2px 0 rgba(0, 0, 0, 0.098), 0 1px 5px 0 rgba(0, 0, 0, 0.084);
            }

            .switch-label .toggle--on {
                display: none;
            }

            .switch-label .toggle--off {
                display: inline-block;
            }

            .switch-input:checked+.switch-label:before {
                background-color: #A5D6A7;
            }

            .switch-input:checked+.switch-label:after {
                background-color: #4CAF50;
                -ms-transform: translate(80%, -50%);
                -webkit-transform: translate(80%, -50%);
                transform: translate(80%, -50%);
            }

            .switch-input:checked+.switch-label .toggle--on {
                display: inline-block;
            }

            .switch-input:checked+.switch-label .toggle--off {
                display: none;
            }

            .parsley-required, .parsley-type, .parsley-minlength, .parsley-pattern {
                padding-top: 5px;
                color: #f00;
                font-size: 12px;
                font-weight: 400;
                display: block;
            }

            .form-group.required .control-label:after {
                content:" *";
                color:red;
            }
        </style>
        @yield('css_after')

        <!-- Scripts -->
        <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>

        <!-- Codebase Core JS -->
        <script src="{{ mix('js/codebase.app.js') }}"></script>

        <!-- Laravel Scaffolding JS-->
        <script src="{{ mix('js/all.js') }}"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        @yield('js_after')
    </head>
    <body>
        <div id="page-container" class="sidebar-inverse side-scroll page-header-fixed page-header-inverse main-content-boxed">
            <!-- Sidebar -->
            <nav id="sidebar">
                <!-- Sidebar Scroll Container -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <div class="sidebar-content">
                        <!-- Side Header -->
                        <div class="content-header content-header-fullrow bg-black-op-10">
                            <div class="content-header-section text-center align-parent">
                                <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                                <!-- END Close Sidebar -->

                                <!-- Logo -->
                                <div class="content-header-item">
                                    <a class="link-effect font-w700" href="#">
                                        <i class="fa fa-medkit text-success"></i>
                                        <span class="font-size-xl text-primary">{{ app_name() }}</span>
                                    </a>
                                </div>
                                <!-- END Logo -->
                            </div>
                        </div>
                        <!-- END Side Header -->

                        <!-- Side Main Navigation -->
                        <div class="content-side content-side-full">
                            @include('frontend.includes.nav-sidebar')
                        </div>
                        <!-- END Side Main Navigation -->
                    </div>
                    <!-- Sidebar Content -->
                </div>
                <!-- END Sidebar Scroll Container -->
            </nav>
            <!-- END Sidebar -->

            <!-- Header -->
            <header id="page-header">
                <!-- Header Content -->
                <div class="content-header">
                    <!-- Left Section -->
                    <div class="content-header-section">
                        <!-- Logo -->
                        <div class="content-header-item">
                            <a class="link-effect font-w700 mr-5" href="{{ route('frontend.index') }}">
                                <i class="fa fa-medkit text-success"></i>
                                <span class="font-size-xl text-primary">{{ app_name() }}</span>
                            </a>
                        </div>
                        <!-- END Logo -->
                    </div>
                    <!-- END Left Section -->

                    <!-- Middle Section -->
                    @include('frontend.includes.nav-header')
                    <!-- END Middle Section -->

                    <!-- Right Section -->
                    @include('frontend.includes.nav-header-right')
                    <!-- END Right Section -->
                </div>
                <!-- END Header Content -->

                <!-- Header Search -->
                <div id="page-header-search" class="overlay-header">
                    <div class="content-header content-header-fullrow">
                        @include('frontend.includes.form-search')
                    </div>
                </div>
                <!-- END Header Search -->

                <!-- Header Loader -->
                <div id="page-header-loader" class="overlay-header bg-primary">
                    <div class="content-header content-header-fullrow text-center">
                        <div class="content-header-item">
                            <i class="fa fa-sun-o fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
                <!-- END Header Loader -->
            </header>
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                <div class="bg-body-dark bg-pattern" style="background-image: url({{ asset('media/various/bg-pattern-inverse.png') }});">
                    <!-- Hero -->
                    @include('includes.partials.logged-in-as')
                    @include('frontend.partials.hero')
                    <!-- END Hero -->

                    <!-- Page Content -->
                    <div class="content content-full">
                        @include('includes.partials.messages')
                        @yield('content')
                    </div>
                    @include('frontend.partials.outer-content')
                    <!-- END Page Content -->
                </div>
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="opacity-0">
                <div class="content py-20 font-size-xs clearfix">
                    <div class="float-right">
                        @lang('labels.general.copyright') &copy; {{ date('Y') }} <i class="fa fa-heart text-pulse"></i> by <a class="font-w600" href="{{ setting()->get('copyright_link') }}" title="{{ setting()->get('copyright') }}" target="_blank">{{ setting()->get('copyright') }}</a> <span>@lang('strings.backend.general.all_rights_reserved')</span>
                    </div>
                    <div class="float-left">
                        <a class="font-w600" href="{{ route('admin.dashboard') }}">{{ app_name() }}</a> {{ config('app.version') }}
                    </div>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->
    </body>
</html>
