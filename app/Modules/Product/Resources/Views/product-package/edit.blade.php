@extends('backend.layouts.app')

@section('title', 'Edit Paket Produk')

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('product::labels.product.management') <small class="text-muted">@lang('product::labels.product.edit')</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->modelForm($productpackage, 'PATCH', route('admin.product.productpackage.update', $productpackage))->class('form-horizontal js-validation-bootstrap')->open() }}
            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label('Nama Paket')
                            ->class('col-md-2 form-control-label')
                            ->for('name') }}

                        <div class="col-md-10">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder('Nama Paket')
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label('Keterangan')
                            ->class('col-md-2 form-control-label')
                            ->for('description') }}

                        <div class="col-md-10">
                            {{ html()->text('description')
                                ->class('form-control')
                                ->placeholder('Keterangan')
                                ->attribute('maxlength', 128)
                                ->required() }}
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label('Diskon')
                            ->class('col-md-2 form-control-label')
                            ->for('discount') }}

                        <div class="col-md-10">
                            <input type="number" name="discount" id="discount" value="0" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.general.status'))
                            ->class('col-md-2 form-control-label')
                            ->for('status') }}

                        <div class="col-md-10">
                            <input type="checkbox" id="is_active" name="is_active" class="switch-input" checked="checked">
                            <label for="is_active" class="switch-label"><span class="toggle--on">@lang('labels.general.active')</span><span class="toggle--off">@lang('labels.general.inactive')</span></label>
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->

                <div class="col-md-12">
                    <span id="error"></span>
                    <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"><button type="button" class="btn btn-sm btn-circle btn-outline-success add" title="Tambah Item">
                                        <i class="fa fa-plus"></i>
                                    </button></th>
                                <th style="width: 50px;"> # </th>
                                <th> Produk </th>
                                <th> Jumlah </th>
                            </tr>
                        </thead>
                        <tbody>
                            {!! $htmlDetail !!}
                        </tbody>
                    </table>
                </div>
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
            'category_id': {
                required: true
            },
            'name': {
                required: true
            },
            'product': {
                required: true
            },
            'price': {
                required: true,
                minlength: 5,
                maxlength: 12,
                number: true
            },
        },
    });

    Codebase.helpers(['select2']);

    $('.product').select2({
        placeholder: "Pilih"
    });

    var i = {!! $num !!};

    $(document).on('click', '.add', function() {
        var htmlDropdown = '<select class="form-control product required" id="' + (i + 1) + 'select2" name="category_id[' + (i + 1) + ']" data-placeholder="Pilih" style="width: 100%">{!! $product !!}</select>';
        var html = '';
        html += '<tr>';
        html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
        html += '<td class="nomor">' + (i + 1) + '</td>';
        html += '<td>' + htmlDropdown + '</td>';
        html += '<td><input type="number" name="qty[' + (i + 1) + ']" class="form-control qty required" value="1" /></td>';

        html += '</tr>';

        $('#tab_logic > tbody').append(html);

        $('#' + (i + 1) + 'select2').select2({
            placeholder: "Pilih"
        });

        i++;
        updateIds();
    });

    $(document).on('click', '.remove', function() {
        $(this).closest('tr').remove();
        updateIds();
    });

    function updateIds() {
        $('#tab_logic tbody tr').each(function(i, element) {
            var html = $(this).html();
            if (html != '') {
                $(this).find('.nomor').text((i+1));
                $(this).find('.product').attr('name', 'product['+(i+1)+']');
                $(this).find('.qty').attr('name', 'qty['+(i+1)+']');
            }
        });

        validator.resetForm();
    }
    updateIds();
});


</script>
@endsection
