@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.login_box_title'))

@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-6 align-self-center">
            <div class="block block-themed block-rounded block-shadow">
                <div class="block-header bg-gd-pulse">
                    <h3 class="block-title">@lang('labels.frontend.auth.login_box_title')
                    </strong></h3>
                </div>
                <div class="block-content">
                    <form class="js-validation-signin px-30" method="POST" action="{{ route('frontend.auth.login.post') }}">
                    @csrf
                        <div class="form-group {{ $errors->has('username') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="username">{{ __('validation.attributes.frontend.username') }}</label>
                                <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" autofocus>

                                @if ($errors->has('username'))
                                    <div id="username-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('username') }}</div>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group {{ $errors->has('password') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="password">{{ __('validation.attributes.frontend.password') }}</label>
                                <input type="password" class="form-control" id="password" name="password">
                                @if ($errors->has('password'))
                                    <div id="username-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-sm-6 d-sm-flex align-items-center push">
                                <div class="custom-control custom-checkbox mr-auto ml-0 mb-0">
                                    <input type="checkbox" class="custom-control-input" id="login-remember-me" name="remember">
                                    <label class="custom-control-label" for="login-remember-me">{{ __('labels.frontend.auth.remember_me') }}</label>
                                </div>
                            </div>
                            <div class="col-sm-6 text-sm-right push">
                                <button type="submit" class="btn btn-alt-primary">
                                    <i class="si si-login mr-10"></i> {{ __('labels.frontend.auth.login_button') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="row">
                        <div class="col">
                            <div class="text-center">
                                {!! $socialiteLinks !!}
                            </div>
                        </div><!--col-->
                    </div><!--row-->
                </div>
                <div class="block-content bg-body-light">
                    <div class="form-group text-center">
                        @if(config('access.registration'))
                        <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="{{ route('frontend.auth.register') }}">
                            <i class="fa fa-plus mr-5"></i> @lang('navs.frontend.register')
                        </a>
                        @endif
                        <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="{{ route('frontend.auth.password.reset') }}">
                            <i class="fa fa-warning mr-5"></i> @lang('labels.frontend.passwords.forgot_password')
                        </a>
                    </div>
                </div>
            </div><!--card-->
        </div><!-- col-md-8 -->
    </div><!-- row -->

    <script src="{{ asset('js/pages/op_auth_signin.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("form").submit(function() {
                $("button").attr("disabled", true);

            });
        });
    </script>
@endsection
