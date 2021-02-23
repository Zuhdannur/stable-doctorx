@extends('base.app')

@section('title', __('product::labels.service.management') . ' | Form Klinik ')

@section('content')
    <div class="block">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('product::labels.service.management') <small class="text-muted">@lang('product::labels.service.create')</small></h3>

        </div>
        <div class="block-content">
            @if(empty($isEdit))
            {{ html()->form('POST', route('klinik.store'))->class('js-validation-bootstrap form-horizontal')->attributes(['autocomplete' => 'off'])->open() }}
            @else
                {{ html()->form('POST', route('klinik.update', @$data->id_klinik))->class('js-validation-bootstrap form-horizontal')->attributes(['autocomplete' => 'off'])->open() }}
                {{ method_field('PATCH') }}
            @endif

            @php

                @endphp
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label("Nama Klinik")
                            ->class('col-md-2 form-control-label')
                            ->for('section_id') }}

                        <div class="col-md-10">
                            {{ html()->text('nama_klinik')
                                ->class('form-control')
                                ->placeholder("Nama Klinik")
                                ->required()
                                ->autofocus()->value(@$data->nama_klinik) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label("Alamat")
                            ->class('col-md-2 form-control-label')
                            ->for('section_id') }}

                        <div class="col-md-10">
                            {{ html()->text('alamat')
                                ->class('form-control')
                                ->placeholder("Alamat Klinik")
                                ->required()
                                ->autofocus()->value(@$data->alamat) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('klinik.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    @if(empty($isEdit))
                    {{ form_submit(__('buttons.general.crud.create')) }}
                    @else
                        {{ form_submit("Ubah") }}
                    @endif
                </div><!--col-->
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>

    <script type="text/javascript">
        jQuery(function () {
            Codebase.helpers(['select2']);
        });

        var BeFormValidation = function() {
            var initValidationBootstrap = function(){
                jQuery('.js-validation-bootstrap').validate({
                    ignore: [],
                    errorClass: 'invalid-feedback animated fadeInDown',
                    errorElement: 'div',
                    errorPlacement: function(error, e) {
                        jQuery(e).parents('.form-group > div').append(error);
                    },
                    highlight: function(e) {
                        jQuery(e).closest('.form-group').removeClass('is-invalid').addClass('is-invalid');
                    },
                    success: function(e) {
                        jQuery(e).closest('.form-group').removeClass('is-invalid');
                        jQuery(e).remove();
                    },
                    rules: {
                        'name': {
                            required: true
                        },
                        'price': {
                            required: true,
                            minlength: 5,
                            maxlength: 12,
                            number: true
                        },
                    },
                });
            };

            return {
                init: function () {
                    // Init Bootstrap Forms Validation
                    initValidationBootstrap();

                    // Init Validation on Select2 change
                    jQuery('.js-select2').on('change', function(){
                        jQuery(this).valid();
                    });
                }
            };
        }();

        // Initialize when page loads
        jQuery(function(){ BeFormValidation.init(); });
    </script>
@endsection
