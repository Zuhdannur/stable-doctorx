@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title'))

@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-8 align-self-center">
            <div class="block block-themed block-rounded block-shadow">
                <div class="block-header bg-gd-pulse">
                    <h3 class="block-title">@lang('labels.frontend.auth.register_box_title')
                    </strong></h3>
                </div>
                <div class="block-content">
                    <form class="js-validation-signup px-30" action="{{ route('frontend.auth.register.post') }}" method="post" autocomplete="off">
                    @csrf
                        <div class="form-group {{ $errors->has('first_name') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="first_name">{{ __('validation.attributes.frontend.first_name') }}</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" autocomplete="off" value="{{ old('first_name') }}" autofocus>

                                @if ($errors->has('first_name'))
                                    <div id="first_name-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group {{ $errors->has('last_name') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="last_name">{{ __('validation.attributes.frontend.last_name') }}</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" autocomplete="off" value="{{ old('last_name') }}">
                                @if ($errors->has('last_name'))
                                    <div id="last_name-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('username') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="signup-username">{{ __('validation.attributes.frontend.username') }}</label>
                                <input type="text" class="form-control" id="username" name="username" autocomplete="off" value="{{ old('username') }}">
                                @if ($errors->has('username'))
                                    <div id="username-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('username') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="email">{{ __('validation.attributes.frontend.email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" autocomplete="off" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <div id="email-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('password') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="password">{{ __('validation.attributes.frontend.password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" autocomplete="off">
                                @if ($errors->has('password'))
                                    <div id="password-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('password_confirmation') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="password_confirmation">{{ __('validation.attributes.frontend.password_confirmation') }}</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                @if ($errors->has('password_confirmation'))
                                    <div id="password_confirmation-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('password_confirmation') }}</div>
                                @endif
                            </div>
                        </div>
                        @if(config('access.captcha.registration'))
                        <div class="row">
                            <div class="col">
                                {!! Captcha::display() !!} {{ html()->hidden('captcha_status', 'true') }}
                            </div>
                            <!--col-->
                        </div>
                        <!--row-->
                        @endif
                        <div class="form-group row mb-0">
                            <div class="col-sm-6 push">
                                <button type="submit" class="btn btn-alt-success">
                                    <i class="si si-login mr-10"></i> {{ __('labels.frontend.auth.register_button') }}
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
                        <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="{{ route('frontend.auth.login') }}">
                            <i class="fa fa-user text-muted mr-5"></i> @lang('navs.frontend.login')
                        </a>
                    </div>
                </div>
            </div><!--card-->
        </div><!-- col-md-8 -->
    </div><!-- row -->

    <script src="{{ asset('js/pages/op_auth_signup.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("form").submit(function() {
                $("button").attr("disabled", true);

            });
        });
    </script>
        @push('after-scripts')
            @if(config('access.captcha.registration'))
                {!! Captcha::script() !!}
            @endif
        @endpush
@endsection
