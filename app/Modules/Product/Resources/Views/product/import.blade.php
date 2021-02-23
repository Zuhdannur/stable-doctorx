@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('product::labels.product.management'))

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.product.management') <small class="text-muted">@lang('product::labels.product.create')</small></h3>
        
    </div>
    <div class="block-content">
        <form class="form-vertical" method="post" action="{{ route('admin.product.import.store') }}" id="formGroup" enctype="multipart/form-data" autocomplete="off" data-parsley-validate>
        	@method('POST')
			@csrf
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('product::validation.import.category'))
                            ->class('col-md-2 form-control-label')
                            ->for('category_id') }}

                        <div class="col-md-10">
                            <select class="js-select2 form-control" id="category_id" name="category_id" data-parsley-required="true" data-placeholder="Pilih" style="width: 100%">
                            	<option></option>
								@foreach ($category as $item)
									<option value="{{ $item->id }}"> {{ $item->name }} </option>
								@endforeach    
							</select>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('product::validation.import.file'))
                            ->class('col-md-2 form-control-label')
                            ->for('file') }}

                        <div class="col-md-10">
                            <div class="form-group required {{ $errors->has('file_excel') ? ' has-error' : '' }}">
			                	<label for="file_excel">File ( .xls atau .xlsx )</label>
			                	<input type="file" id="file_excel" name="file_excel" class="dropify form-control" data-height="20" data-max-file-size="1M" data-allowed-file-extensions="xls xlsx" />
			                </div>
                        </div>
                    </div>
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
        </form>
    </div>
</div>
<script type="text/javascript">
jQuery(function () {
    Codebase.helpers(['select2']);
});
</script>
@endsection