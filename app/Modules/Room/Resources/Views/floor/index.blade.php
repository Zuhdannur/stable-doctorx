@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('room::labels.floor.management'))

@section('content')
<div class="block" id="my-block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('room::labels.floor.management')</h3>
        <div class="block-options">
            <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">
                <a href="{{ route('admin.room.floor.create') }}" class="btn btn-sm btn-alt-info" data-toggle="tooltip" title="@lang('labels.general.create_new')">
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
                    <th>@lang('room::labels.floor.table.name')</th>
                    <!-- <th>@lang('labels.general.created_at')</th> -->
                    <!-- <th>@lang('labels.general.updated_at')</th> -->
                    <th class="text-center" style="width: 100px;">@lang('labels.general.actions')</th>
                </tr>
            </thead>
           
        </table>
    </div>
</div>
<script>
    $(function () {
        var dt = $('#table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [[ 1, "asc" ]],
            pageLength: 10,
            ajax: '{!! route('admin.room.floor.index') !!}',
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
                    data: 'name',
                    name: 'name',
                    className: 'font-w600'
                },
                /*{
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },*/
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