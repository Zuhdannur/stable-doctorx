<!doctype html>
<!--[if lte IE 9]>     <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-focus lt-ie10 lt-ie10-msg"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <meta name="description" content="{{ config('app.name') }}">
        <meta name="author" content="{{ config('app.name') }}">
        <meta name="robots" content="noindex, nofollow">

        <!-- Open Graph Meta -->
        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:description" content="{{ config('app.name') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="">
        <meta property="og:image" content="">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="media/favicons/favicon.png">
        <link rel="icon" type="image/png" sizes="192x192" href="media/favicons/favicon-192x192.png">
        <link rel="apple-touch-icon" sizes="180x180" href="media/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Scripts -->
        <script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.scrollLock.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.appear.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/jquery.countTo.min.js') }}"></script>
        <script src="{{ asset('assets/js/core/js.cookie.min.js') }}"></script>
        <script src="{{ asset('assets/js/codebase.js') }}"></script>
        <script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/op_auth_signin.js') }}"></script>

        <!-- Stylesheets -->
        <!-- Codebase framework -->
        <link href="{{ asset('assets/css/codebase.min.css') }}" rel="stylesheet">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body>
        <div id="page-container" class="main-content-boxed">
            <!-- Main Container -->
            <main id="main-container">
                <!-- Page Content -->
                <div class="bg-image" style="background-image: url('media/photos/photo34@2x.jpg');">
                    <div class="row mx-0 bg-black-op">
                        <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
                            <div class="p-30 invisible" data-toggle="appear">
                                <p class="font-size-h3 font-w600 text-white">
                                    {{ config('app.name') }}
                                </p>
                                <p class="font-italic text-white-op">
                                    Copyright &copy; <span class="js-year-copy"></span>
                                </p>
                            </div>
                        </div>
                        <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible" data-toggle="appear" data-class="animated fadeInRight">
                            <div class="content content-full">
                                <!-- Header -->
                                <div class="px-30 py-10">
                                    <a class="link-effect font-w700" href="{{ route('home') }}">
                                        <i class="si si-fire"></i>
                                        <span class="font-size-xl text-primary-dark">{{ config('app.name') }}</span>
                                        <span class="font-size-xl">{{ config('app.version') }}</span>
                                    </a>
                                    <h1 class="h5 font-w700 mt-30 mb-10">Masukan Username dan Password.</h1>
                                </div>
                                <!-- END Header -->
                                <!-- Sign In Form -->
                                <!-- jQuery Validation (.js-validation-signin class is initialized in js/pages/op_auth_signin.js) -->
                                <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                                <form class="js-validation-signin px-30" method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group {{ $errors->has('username') ? ' is-invalid' : '' }} row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" autofocus>
                                                <label for="username">{{ __('Username') }}</label>
                                            </div>
                                            @if ($errors->has('username'))
                                                <div id="username-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('username') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group {{ $errors->has('password') ? ' is-invalid' : '' }} row">
                                        <div class="col-12">
                                            <div class="form-material floating">
                                                <input type="password" class="form-control" id="password" name="password">
                                                <label for="password">{{ __('Password') }}</label>
                                            </div>
                                            @if ($errors->has('password'))
                                                <div id="username-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('password') }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="login-remember-me">{{ __('Ingatkan Saya') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-sm btn-hero btn-alt-primary">
                                            <i class="si si-login mr-10"></i> {{ __('Log Masuk') }}
                                        </button>
                                    </div>
                                </form>
                                <!-- END Sign In Form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Page Content -->
            </main>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->
    </body>
</html>