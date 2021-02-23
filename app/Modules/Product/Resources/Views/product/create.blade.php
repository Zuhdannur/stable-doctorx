@extends('backend.layouts.app')

@section('title', __('product::labels.product.management') . ' | ' . __('product::labels.product.create'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.product.management') <small class="text-muted">@lang('product::labels.product.create')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->form('POST', route('admin.product.store'))->class('js-validation-bootstrap form-horizontal')->attributes(['autocomplete' => 'off'])->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.category'))
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
                        {{ html()->label(__('product::validation.product.code'))
                            ->class('col-md-2 form-control-label')
                            ->for('code') }}

                        <div class="col-md-10">
                            {{ html()->text('code')
                                ->class('form-control')
                                ->placeholder(__('product::validation.product.code'))
                                ->attribute('maxlength', 128)
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('product::validation.product.name'))
                                ->attribute('maxlength', 128)
                                ->required() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.purchase_price'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-2">
                            <input type="text" class="form-control tanpa-rupiah" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') ? old('purchase_price') : 0}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.price_sales'))
                            ->class('col-md-2 form-control-label')
                            ->for('price') }}

                        <div class="col-md-2">
                            <input type="text" class="form-control tanpa-rupiah" id="price" name="price" value="{{ currency()->rupiah(old('price') ? old('price') : 0) }}">
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        {{ html()->label(__('product::validation.product.percentage_price_sales')." (%)")
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-2">
                            <input type="text" class="form-control" id="percentage_price_sales" name="percentage_price_sales" value="{{ old('percentage_price_sales') ? old('percentage_price_sales') : ''}}">
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        {{ html()->label('Jumlah Stok')
                            ->class('col-md-2 form-control-label')
                            ->for('quantity') }}

                        <div class="col-md-2">
                            <input type="number" id="quantity" name="quantity" class="form-control" min="0" value="{{ old('quantity') ? old('quantity') : 0}}"/>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label('Stock Minimum')
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-2">
                            <input type="number" class="form-control" id="min_stock" name="min_stock" value="{{ old('min_stock') ? old('min_stock') : ''}}">
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        {{ html()->label(__('product::validation.product.type_sales'))
                        ->class('col-md-2 form-control-label')
                        ->for('type_sales') }}

                        <div class="col-md-4">
                            <select name="type_sales" id="type_sales" class="form-control">
                                <option value="0" >Harga Beli Rata-Rata</option>
                                <option value="1" selected >Harga Beli Terakhir</option>
                            </select>
                        </div>
                    </div> --}}

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
                    {{ form_cancel(route('admin.product.index'), __('buttons.general.cancel')) }}
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
    $('#type_sales').select2({
        placeholder : true
    })
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
                'category_id': {
                    required: true
                },
                'name': {
                    required: true
                },
                /* 'price': {
                    required: true,
                    minlength: 5,
                    maxlength: 12,
                    number: true
                }, */
                'percentage_price_sales':{
                    required: true,
                    number: true,
                }
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
