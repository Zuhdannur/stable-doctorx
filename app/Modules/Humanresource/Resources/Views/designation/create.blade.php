@extends('backend.layouts.app')

@section('title', 'Tambah Posisi')

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Posisi <small class="text-muted">Tambah Data</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->form('POST', route('admin.humanresource.designation.store'))->class('form-horizontal js-validation-bootstrap')->open() }}
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
                    {{ form_cancel(route('admin.humanresource.designation.index'), __('buttons.general.cancel')) }}
                </div>

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
<script type="text/javascript">
jQuery(function() {

    var validator = jQuery('.js-validation-bootstrap').validate({
        ignore: [],
        errorClass: 'invalid-feedback animated fadeInDown',
        errorElement: 'div',
        focusInvalid: true,
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                var firstInvalidElement = $(validator.errorList[0].element);
                $('html,body').scrollTop(firstInvalidElement.offset().top);
            }
        },
        highlight: function(e) {
            jQuery(e).closest('td').removeClass('is-invalid').addClass('is-invalid');
        },
        success: function(e) {
            jQuery(e).closest('td').removeClass('is-invalid');
            jQuery(e).remove();
        },
        checkForm: function() {
            this.prepareForm();
            for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
                if (this.findByName(elements[i].name).length != undefined && this.findByName(elements[i].name).length > 1) {
                    for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                        this.check(this.findByName(elements[i].name)[cnt]);
                    }
                } else {
                    this.check(elements[i]);
                }
            }
            return this.valid();
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
