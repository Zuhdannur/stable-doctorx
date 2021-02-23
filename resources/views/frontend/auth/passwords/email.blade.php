@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.passwords.reset_password_box_title'))

@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-8 align-self-center">
            <div class="block block-themed block-rounded block-shadow">
                <div class="block-header bg-gd-pulse">
                    <h3 class="block-title">@lang('labels.frontend.passwords.reset_password_box_title')
                    </strong></h3>
                </div>
                <div class="block-content">
                    <div class="font-w400 text-muted mb-0">
                        @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                    </div>
                    <form class="js-validation-reminder px-30" action="{{ route('frontend.auth.password.email.post') }}" method="post">
                    @csrf
                        <div class="form-group {{ $errors->has('username') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="reminder-credential">{{ __('validation.attributes.frontend.email') }}</label>
                                <input type="text" class="form-control" id="email" name="email">

                                @if ($errors->has('email'))
                                    <div id="email-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-sm-6 push">
                                <button type="submit" class="btn btn-alt-primary">
                                    <i class="fa fa-asterisk mr-10"></i> {{ __('labels.frontend.passwords.send_password_reset_link_button') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="block-content bg-body-light">
                    <div class="form-group text-center">
                        <a class="link-effect text-muted mr-10 mb-5 d-inline-block" href="{{ route('frontend.auth.password.reset') }}">
                            <i class="fa fa-user text-muted mr-5"></i> @lang('navs.frontend.login')
                        </a>
                    </div>
                </div>
            </div><!--card-->
        </div><!-- col-md-8 -->
    </div><!-- row -->

    <script src="{{ URL::asset('js/pages/op_auth_reminder.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("form").submit(function() {
                $("button").attr("disabled", true);

            });
        });
    </script>
@endsection
