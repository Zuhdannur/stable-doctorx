@extends('backend.layouts.app')

@section('title', __('product::labels.service.management') . ' | ' . __('product::labels.service.create'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.service.management') <small class="text-muted">@lang('product::labels.service.create')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->form('POST', route('admin.product.service.store'))->class('js-validation-bootstrap form-horizontal')->attributes(['autocomplete' => 'off'])->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('product::validation.service.category'))
                            ->class('col-md-2 form-control-label')
                            ->for('section_id') }}

                        <div class="col-md-10">
                            <select class="js-select2 form-control" id="category_id" name="category_id" data-placeholder="Pilih" style="width: 100%">
                                <option></option>

                                 @foreach ($category as $item)
                                    <option value="{{ $item['id'] }}" {{ ( $item['id'] == old('category_id')) ? 'selected' : '' }}> {{ $item['name'] }} </option>
                                @endforeach  
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.service.code'))
                            ->class('col-md-2 form-control-label')
                            ->for('code') }}

                        <div class="col-md-10">
                            {{ html()->text('code')
                                ->class('form-control')
                                ->placeholder(__('product::validation.service.code'))
                                ->attribute('maxlength', 12)
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.service.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('product::validation.service.name'))
                                ->attribute('maxlength', 128)
                                ->required() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.service.price'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('price')
                                ->class('form-control  tanpa-rupiah')
                                ->placeholder(__('product::validation.service.price'))
                                ->attribute('maxlength', 128)
                                ->attribute('placeholder', 0)
                                ->required() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.general.status'))
                            ->class('col-md-2 form-control-label')
                            ->for('status') }}

                        <div class="col-md-10">
                            <input type="checkbox" id="is_active" name="is_active" class="switch-input" checked="checked">
                            <label for="is_active" class="switch-label"><span class="toggle--on">@lang('labels.general.active')</span><span class="toggle--off">@lang('labels.general.inactive')</span></label>
                        </div><!--col-->
                    </div><!--form-group-->
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.product.service.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
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
