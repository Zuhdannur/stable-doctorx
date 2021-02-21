@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.frontend.contact.box_title'))

@section('content')
    <div class="row justify-content-center align-items-center">
        <div class="col col-sm-8 align-self-center">
            <div class="block block-themed block-rounded block-shadow">
                <div class="block-header bg-gd-pulse">
                    <h3 class="block-title">@lang('labels.frontend.contact.box_title')
                    </strong></h3>
                </div>
                <div class="block-content">
                    <form class="js-validation-be-contact px-30" method="POST" action="{{ route('frontend.contact.send') }}">
                    @csrf
                        <div class="form-group {{ $errors->has('name') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="name">{{ __('validation.attributes.frontend.name') }}</label>
                                {{ html()->text('name', optional(auth()->user())->name)
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.name'))
                                        ->attribute('maxlength', 191)
                                        ->required()
                                        ->autofocus() }}

                                @if ($errors->has('name'))
                                    <div id="name-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="email">{{ __('validation.attributes.frontend.email') }}</label>
                                {{ html()->email('email', optional(auth()->user())->email)
                                        ->class('form-control')
                                        ->placeholder(__('validation.attributes.frontend.email'))
                                        ->attribute('maxlength', 191)
                                        ->required() }}

                                @if ($errors->has('email'))
                                    <div id="email-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group {{ $errors->has('phone') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="username">{{ __('validation.attributes.frontend.phone') }}</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">

                                @if ($errors->has('phone'))
                                    <div id="phone-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group {{ $errors->has('message') ? ' is-invalid' : '' }} row">
                            <div class="col-12">
                                <label for="message">{{ __('validation.attributes.frontend.message') }}</label>
                                <textarea class="form-control" id="message" name="message" value="{{ (auth()->user() ? old('message') : null) }}" rows="3"></textarea>

                                @if ($errors->has('message'))
                                    <div id="message-error" class="invalid-feedback animated fadeInDown">{{ $errors->first('message') }}</div>
                                @endif
                            </div>
                            
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-sm-6 push">
                                <button type="submit" class="btn btn-alt-primary">
                                    <i class="si si-login mr-10"></i> {{ __('labels.frontend.contact.button') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!--card-->
        </div><!-- col-md-8 -->
    </div><!-- row -->
    <script src="{{ URL::asset('assets/js/pages/be_pages_generic_contact.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("form").submit(function() {
                $("button").attr("disabled", true);

            });
        });
    </script>
@endsection
