<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', app_name())">
    <meta name="author" content="@yield('meta_author', app_name())">
    @yield('meta')

    <!-- Icons -->
    <link rel="shortcut icon" type="image/png" href="{{ asset(setting()->get('favicon')) }}" />
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset(setting()->get('favicon')) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset(setting()->get('favicon')) }}">
    <!-- END Icons -->

    <!-- Fonts and Styles -->
    @yield('css_before')
    <link rel="stylesheet" href="{{ URL::asset('js/plugins/fullcalendar/fullcalendar.min.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,400i,600,700">

    <link rel="stylesheet" id="css-theme" href="{{ asset('css/all.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('css/codebase.css') }}">
    <link rel="stylesheet" id="css-theme" href="{{ asset('css/themes/corporate.css') }}">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};</script>

    <!-- Codebase Core JS -->
    <script src="{{ asset('js') }}/codebase.app.js"></script>

    <!-- Laravel Scaffolding JS -->
    <script src="{{ asset('js/all.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script type="text/javascript">
            $(function() {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.fn.select2.defaults.set('language', 'id');
            });
        </script>
    @yield('js_after')
</head>

<body class="{{ config('backend.body_classes') }}">
    <div id="page-container" class="{{ config('backend.container_classes') }}">
        <!-- Side Overlay-->
        @include('backend.includes.aside')
        <!-- END Side Overlay -->

        <!-- Sidebar -->
        @include('backend.includes.sidebar')
        <!-- END Sidebar -->

        <!-- Header -->
        @include('backend.includes.header')
        <!-- END Header -->

        <!-- Main Container -->


        <main id="main-container">
            {!! Breadcrumbs::render() !!}
            @include('backend.includes.shortcut')
            @include('includes.partials.logged-in-as')
            <div class="content">
                @yield('page-header')
                @include('includes.partials.messages')
                @yield('content')
            </div>
        </main><!--main-->
        <!-- END Main Container -->

        <!-- Footer -->
        @include('backend.includes.footer')
        <!-- END Footer -->
    </div>
    @stack('js')
</body>
</html>
