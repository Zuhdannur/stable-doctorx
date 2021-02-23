@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('billing::labels.billing.management'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('billing::labels.billing.management')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.billing.create', [0,0]) }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
                    <i class="fa fa-random"></i> @lang('labels.general.create_new')
                </a>
            </div>
        </div>
    </div>
    <div class="block-content block-content-full">
        <table id="table" class="table table-sm table-hover table-striped table-vcenter js-dataTable-full-pagination" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>@lang('billing::labels.billing.table.invoice_no')</th>
                    <th>@lang('billing::labels.billing.table.invoice_date')</th>
                    <th>@lang('billing::labels.billing.table.patient_name')</th>
                    <th>@lang('billing::labels.billing.table.discount')</th>
                    <th>@lang('billing::labels.billing.table.total')</th>
                    <th>@lang('billing::labels.billing.table.status')</th>
                    <th>@lang('billing::labels.billing.table.note')</th>
                    <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                </tr>
            </thead>
           
        </table>
    </div>
</div>

<script>
    jQuery(function(){ 
        $(document).on("focusout", ".datepicker", function() {
            $(this).prop('readonly', false);
        });

        $(document).on("focusin", ".datepicker", function() {
            $(this).prop('readonly', true);
        });

        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ordering: false,
            pageLength: 50,
            lengthMenu: [50, 100, 125],
            ajax: '{!! route('admin.billing.index') !!}',
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
                    data: 'invoice_no',
                    name: 'invoice_no',
                    className: 'font-w600'
                },
                {
                    data: 'date',
                    name: 'date',
                },
                {
                    data: 'patient_name',
                    name: 'patient.patient_name',
                },
                {
                    "visible": false,
                    data: 'discount_percent',
                    name: 'discount',
                },
                {
                    data: 'total',
                    name: 'total',
                },
                {
                    data: 'status',
                    name: 'status',
                },
                {
                    data: 'note',
                    name: 'note',
                },
                {
                    data: 'action',
                    name: 'action',
                    width: "8%",
                    className: 'text-right',
                    orderable: false, 
                    searchable: false
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
</script>
@endsection