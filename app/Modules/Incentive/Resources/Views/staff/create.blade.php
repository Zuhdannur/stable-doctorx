@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('humanresource::labels.staff.management'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Tambah Data Komisi</h3>
    </div>
    <div class="block-content block-content-full">
        <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th></th>
                    <th>@lang('humanresource::labels.staff.table.name')</th>
                    <th>@lang('humanresource::labels.staff.table.employee_id')</th>
                    <th>@lang('humanresource::labels.staff.table.role')</th>
                    <th>@lang('humanresource::labels.staff.table.designation')</th>
                    <th>@lang('humanresource::labels.staff.table.department')</th>
                </tr>
            </thead>
           
        </table>
    </div>
</div>

<div class="block" id="my-block2">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.product.management') <small class="text-muted">Pilih Komisi</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->form('POST', route('admin.incentive.staff.store'))->class('js-validation-bootstrap form-horizontal')->attributes(['autocomplete' => 'off'])->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('product::validation.product.category'))
                            ->class('col-md-2 form-control-label')
                            ->for('section_id') }}

                        <div class="col-md-10">
                            <select class="js-select2 form-control" id="incentive_id" name="incentive_id" data-placeholder="Pilih" style="width: 100%">
                                <option></option>

                                 @foreach ($incentive as $item)
                                    <option value="{{ $item['id'] }}" {{ ( $item['id'] == old('id')) ? 'selected' : '' }}> {{ $item['name'] }} </option>
                                @endforeach  
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.incentive.staff.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div>
        {{ html()->form()->close() }}
    </div>
</div>

<script>
    $(function () {
        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            pageLength: 10,
            ajax: '{!! route('admin.incentive.staff.list') !!}',
            language: {
                url: "{!! URL::asset('js/plugins/datatables/i18n/'.str_replace('_', '-', app()->getLocale()).'.json') !!}"
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'id',
                    width: "5%",
                    orderable: false, 
                    searchable: false
                },
                {
                    data: 'checkbox',
                    name: 'checkbox',
                },
                {
                    data: 'full_name',
                    name: 'full_name',
                    className: 'font-w600'
                },
                {
                    data: 'employee_id',
                    name: 'employee_id',
                },
                {
                    data: 'role',
                    name: 'role',
                },
                {
                    data: 'designation',
                    name: 'designation',
                },
                {
                    data: 'department',
                    name: 'department',
                }
            ]
        }).on('processing.dt', function (e, settings, processing) {
            if (processing) {
                Codebase.blocks('#my-block', 'state_loading');
            } else {
                Codebase.blocks('#my-block', 'state_normal');
            }
        }).on('draw.dt', function () {
            $('[data-toggle="tooltip"]').tooltip();
            addDeleteForms();
        } );
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
                    'incentive_id': {
                        required: true
                    },
                },
                submitHandler: function(form) {
                    var formData = new FormData(form);
                    var staffId = [];

                    $('.staffId:checked').each(function(){
                        staffId.push($(this).val());
                    });
                    // alert(staffId); return false;
                    formData.append('staffId', staffId);
                    $.ajax({
                        type: 'POST',
                        url: $(form).attr('action'),
                        data: formData,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        beforeSend: function(xhr) {
                            Codebase.blocks('#my-block2', 'state_loading');
                        },
                        error: function(x, status, error) {
                            if (x.status === 422) {
                                var errors = x.responseJSON;
                                var errorsHtml = '';
                                $.each(errors['errors'], function(index, value) {
                                    errorsHtml += '<ul><li class="text-danger">' + value + '</li></ul>';
                                });

                                $.alert({
                                    title: "Error " + x.status + ': ' + error,
                                    icon: 'fa fa-warning',
                                    type: 'red',
                                    content: errorsHtml,
                                    columnClass: 'col-md-5 col-md-offset-3',
                                    typeAnimated: true,
                                    draggable: false,
                                });

                            } else {
                                var errors = x.responseJSON;
                                $.alert({
                                    title: x.status + ': ' + error,
                                    icon: 'fa fa-warning',
                                    type: 'red',
                                    content: errors.message,
                                });
                            }

                            Codebase.blocks('#my-block2', 'state_normal');
                        },
                        success: function(result) {
                            if (result.status) {
                                $('#modalForm').modal('hide');
                                
                                $.confirm({
                                    title: "Ok",
                                    icon: 'fa fa-smile-o',
                                    theme: 'modern',
                                    closeIcon: true,
                                    content: result.message,
                                    animation: 'scale',
                                    type: 'blue',
                                    buttons: {
                                        close: function(){
                                            window.location.replace('{!! route('admin.incentive.staff.index') !!}');
                                        }
                                    }
                                });
                            } else {
                                $.alert({
                                    title: "Error",
                                    icon: 'fa fa-warning',
                                    type: 'red',
                                    content: result.message,
                                    columnClass: 'col-md-5 col-md-offset-3',
                                    typeAnimated: true,
                                    draggable: false,
                                });
                            }

                            Codebase.blocks('#my-block2', 'state_normal');
                        }
                    });
                    return false; // required to block normal submit since you used ajax
                }
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