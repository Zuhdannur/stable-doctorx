@extends('backend.layouts.app')

@section('title', __('product::labels.category.management') . ' | ' . __('product::labels.category.edit'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.category.management') <small class="text-muted">@lang('product::labels.category.edit')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->modelForm($category, 'PATCH', route('admin.product.category.update', $category))->class('form-horizontal')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('product::validation.category.parent_name'))
                            ->class('col-md-2 form-control-label')
                            ->for('parent_name') }}

                        <div class="col-md-10">
                            <select class="js-select2 form-control" id="parent_id" name="parent_id" style="width: 100%">
                                <option value="">Tidak Ada</option>

                                 @foreach ($dropdownCategory as $item)
                                    <option value="{{ $item['id'] }}" {{ ( $item['id'] == $category['parent_id']) ? 'selected' : '' }}> {{ $item['name'] }} </option>
                                @endforeach  
                            </select>
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.category.name'))
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('product::validation.category.name'))
                                ->attribute('maxlength', 128)
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.general.status'))
                            ->class('col-md-2 form-control-label')
                            ->for('status') }}

                        <div class="col-md-10">
                            <input type="checkbox" id="is_active" name="is_active" class="switch-input" {{ $category->is_active == 1 ? 'checked' : '' }}>
                            <label for="is_active" class="switch-label"><span class="toggle--on">@lang('labels.general.active')</span><span class="toggle--off">@lang('labels.general.inactive')</span></label>
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.product.category.index'), __('buttons.general.cancel')) }}
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
