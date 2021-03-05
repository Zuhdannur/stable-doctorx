@extends('backend.layouts.app')

@section('title', __('product::labels.service.management') . ' | ' . __('product::labels.service.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.service.management') <small class="text-muted">@lang('product::labels.service.edit')</small></h3>

    </div>
    <div class="block-content">
        {{ html()->modelForm($service, 'PATCH', route('admin.product.service.update', $service))->class('form-horizontal')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('product::validation.service.category'))
                            ->class('col-md-2 form-control-label')
                            ->for('parent_name') }}

                        <div class="col-md-10">
                            <select class="js-select2 form-control" id="category_id" name="category_id" style="width: 100%">
                                <option value="">Tidak Ada</option>

                                 @foreach ($dropdownCategory as $item)
                                    <option value="{{ $item['id'] }}" {{ ( $item['id'] == $service['category_id']) ? 'selected' : '' }}> {{ $item['name'] }} </option>
                                @endforeach
                            </select>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.service.code'))
                            ->class('col-md-2 form-control-label')
                            ->for('code') }}

                        <div class="col-md-10">
                            {{ html()->text('code')
                                ->class('form-control')
                                ->placeholder(__('product::validation.service.code'))
                                ->attribute('maxlength', 12)
                                ->required() }}
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
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label("Harga")
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            <input type="text" class="form-control tanpa-rupiah" id="price" name="price" value="{{ currency()->rupiah(old('price') ? old('price') : $service->price) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.general.status'))
                            ->class('col-md-2 form-control-label')
                            ->for('status') }}

                        <div class="col-md-10">
                            <input type="checkbox" id="is_active" name="is_active" class="switch-input" {{ $service->is_active == 1 ? 'checked' : '' }}>
                            <label for="is_active" class="switch-label"><span class="toggle--on">@lang('labels.general.active')</span><span class="toggle--off">@lang('labels.general.inactive')</span></label>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('Flag'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            <select name="flag" id="flag" class="form-control">
                                @foreach(array(array("Dokter","1"), array("Terapis","2"))  as $item)
                                    @if($service->flag == $item[1])
                                        <option value="{{ $item[1] }}" selected>{{ $item[0] }}</option>
                                    @else
                                        <option value="{{ $item[1] }}">{{ $item[0] }}</option>
                                        @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div><!--col-->
            </div><!--row-->

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.product.service.index'), __('buttons.general.cancel')) }}
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
