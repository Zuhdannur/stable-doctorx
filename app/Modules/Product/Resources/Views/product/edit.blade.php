@extends('backend.layouts.app')

@section('title', __('product::labels.product.management') . ' | ' . __('product::labels.product.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.product.management') <small class="text-muted">@lang('product::labels.product.edit')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->modelForm($product, 'PATCH', route('admin.product.update', $product))->class('form-horizontal')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.category'))
                            ->class('col-md-2 form-control-label')
                            ->for('parent_name') }}

                        <div class="col-md-10">
                            <select class="js-select2 form-control" id="category_id" name="category_id" style="width: 100%">
                                <option value="">Tidak Ada</option>

                                 @foreach ($dropdownCategory as $item)
                                    <option value="{{ $item['id'] }}" {{ ( $item['id'] == $product['category_id']) ? 'selected' : '' }}> {{ $item['name'] }} </option>
                                @endforeach  
                            </select>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.code'))
                            ->class('col-md-2 form-control-label')
                            ->for('code') }}

                        <div class="col-md-10">
                            {{ html()->text('code')
                                ->class('form-control')
                                ->placeholder(__('product::validation.product.code'))
                                ->attribute('maxlength', 12)
                                ->required() }}
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
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.purchase_price_avg'))
                            ->class('col-md-2 form-control-label')
                            ->for('purchase_price_avg') }}

                        <div class="col-md-2">
                            <input type="text" class="form-control tanpa-rupiah" id="purchase_price_avg" name="purchase_price_avg" value="{{ currency()->rupiah(old('purchase_price_avg') ? old('purchase_price_avg') : $product->purchase_price_avg) }}" disabled>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.purchase_price'))
                            ->class('col-md-2 form-control-label')
                            ->for('purchase_price') }}

                        <div class="col-md-2">
                            <input type="text" class="form-control tanpa-rupiah" id="purchase_price" name="purchase_price" value="{{ currency()->rupiah(old('purchase_price') ? old('purchase_price') : $product->purchase_price) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.price_sales'))
                            ->class('col-md-2 form-control-label')
                            ->for('price') }}

                        <div class="col-md-2">
                            <input type="text" class="form-control tanpa-rupiah" id="price" name="price" value="{{ currency()->rupiah(old('price') ? old('price') : $product->price) }}">
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        {{ html()->label(__('product::validation.product.percentage_price_sales')." (%)")
                            ->class('col-md-2 form-control-label')
                            ->for('percentage_price_sales') }}

                        <div class="col-md-2">
                            <input type="text" class="form-control" id="percentage_price_sales" name="percentage_price_sales" value="{{ currency()->rupiah(old('percentage_price_sales') ? old('percentage_price_sales') : $product->percentage_price_sales) }}">
                        </div>
                    </div> --}}

                    {{-- <div class="form-group row">
                        {{ html()->label(__('product::validation.product.type_sales'))
                        ->class('col-md-2 form-control-label')
                        ->for('type_sales') }}

                        <div class="col-md-4">
                            <select name="type_sales" id="type_sales" class="form-control">
                                <option value="0" {{ $product->type_sales == 0 ? 'selected' : ''}}>Harga Beli Rata-Rata</option>
                                <option value="1" {{ $product->type_sales == 1 ? 'selected' : ''}}>Harga Beli Terakhir</option>
                            </select>
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        {{ html()->label('Stock Minimum')
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-2">
                            <input type="number" class="form-control" id="min_stock" name="min_stock" value="{{ (old('min_stock') ? old('min_stock') : $product->min_stock) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.general.status'))
                            ->class('col-md-2 form-control-label')
                            ->for('status') }}

                        <div class="col-md-10">
                            <input type="checkbox" id="is_active" name="is_active" class="switch-input" {{ $product->is_active == 1 ? 'checked' : '' }}>
                            <label for="is_active" class="switch-label"><span class="toggle--on">@lang('labels.general.active')</span><span class="toggle--off">@lang('labels.general.inactive')</span></label>
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.product.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div>
        {{ html()->closeModelForm() }}
    </div>
</div>

<script type="text/javascript">
jQuery(function () {
    $('.js-select2').select2();
});
</script>
@endsection
