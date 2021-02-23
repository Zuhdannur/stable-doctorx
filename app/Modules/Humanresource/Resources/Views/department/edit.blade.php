@extends('backend.layouts.app')

@section('title', 'Edit Departemen')

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Departemen <small class="text-muted">Edit Data</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->modelForm($department, 'PATCH', route('admin.humanresource.department.update', $department))->class('form-horizontal js-validation-bootstrap')->open() }}
            <div class="row mt-4">
                <div class="col">
                    
                    <div class="form-group row">
                        {{ html()->label('Nama')
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-6">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder('Nama')
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        {{ html()->label('Deskripsi')
                            ->class('col-md-2 form-control-label')
                            ->for('description') }}

                        <div class="col-md-6">
                            {{ html()->text('description')
                                ->class('form-control')
                                ->placeholder('Deskripsi')
                                ->attribute('maxlength', 191) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.humanresource.department.index'), __('buttons.general.cancel')) }}
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
    var validator = jQuery('.js-validation-bootstrap').validate({
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
        },
    });

});


</script>
@endsection
