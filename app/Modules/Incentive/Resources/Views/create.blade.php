@extends('backend.layouts.app')

@section('title', __('patient::labels.diagnoseitem.management') . ' | Tambah Insentif')

@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Insentif <small class="text-muted">Tambah Data</small></h3>
        
    </div>
    <div class="block-content">
        {{ html()->form('POST', route('admin.incentive.store'))->class('form-horizontal js-validation-bootstrap')->open() }}
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
                    
                    <div class="form-group row">
                        {{ html()->label('Komisi Obat')
                            ->class('col-md-2 form-control-label')
                            ->for('komisiobat') }}

                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="number" class="form-control" id="product_incentive" name="product_incentive" value="{{ old('product_incentive') ?? 0 }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        %
                                    </span>
                                </div>

                            </div>
                            <small class="form-text text-muted">Semua produk/obat akan ditambahkan komisi.</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label('Komisi Point')
                            ->class('col-md-2 form-control-label')
                            ->for('komisiobat') }}

                        <div class="col-md-3">
                            <div class="input-group">
                                <input type="number" class="form-control" id="point_value" name="point_value" value="{{ old('point_value') ?? 0 }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        point
                                    </span>
                                </div>

                            </div>
                            <small class="form-text text-muted">Setiap Obat/Tindakan akan mendapatkan point.</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"> </th>
                                <th style="width: 50px;"> # </th>
                                <th> Produk </th>
                                <th> Komisi/Persentase/Point </th>
                                <th> Nilai </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><button type="button" class="btn btn-sm btn-circle btn-outline-success add" title="Tambah Item">
                                    <i class="fa fa-plus"></i>
                                </button></td>
                                <td class="nomor">
                                    1
                                </td>
                                <td>
                                    <select class="form-control product" id="1select2" name="category_id[1]" data-placeholder="Pilih" style="width: 100%">
                                        {!! $product !!}
                                    </select>
                                </td>
                                <td width="10%">
                                    <select class="form-control producttype" name="producttype[1]" data-placeholder="Pilih" style="width: 100%">
                                        <option></option>
                                        <option value="amount">Komisi</option>
                                        <option value="percent">Persentase</option>
                                        <option value="point">Point</option>
                                    </select>
                                </td>
                                <td width="10%">
                                    <input type="number" name='productvalue[1]' class="form-control productvalue" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-striped table-vcenter" id="tab_logic3">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;"> </th>
                                <th style="width: 50px;"> # </th>
                                <th> Tindakan </th>
                                <th> Komisi/Persentase/Point </th>
                                <th> Nilai </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><button type="button" class="btn btn-sm btn-circle btn-outline-success add3" title="Tambah Item">
                                    <i class="fa fa-plus"></i>
                                </button></td>
                                <td class="nomor3">
                                    1
                                </td>
                                <td>
                                    <select class="form-control service" id="service1" name="service[1]" data-placeholder="Pilih" style="width: 100%">
                                        {!! $service !!}
                                    </select>
                                </td>
                                <td width="10%">
                                    <select class="form-control servicetype" name="servicetype[1]" data-placeholder="Pilih" style="width: 100%">
                                        <option></option>
                                        <option value="amount">Komisi</option>
                                        <option value="percent">Persentase</option>
                                        <option value="point">Point</option>
                                    </select>
                                </td>
                                <td width="10%">
                                    <input type="number" name='serviceqty[1]' class="form-control serviceqty" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="form-group row">
                <div class="col">
                    {{ form_cancel(route('admin.incentive.index'), __('buttons.general.cancel')) }}
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
    Codebase.helpers(['select2']);

    $('#1select2, #service1').select2({
        placeholder: "Pilih"
    });

    var i = 1;
    var k = 1;

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
                //firstInvalidElement.focus();
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
            'category_id': {
                required: true
            },
            'name': {
                required: true
            },
        },
    });

    $(document).on('click', '.add', function() {
        var htmlDropdown = '<select class="form-control product" id="' + (i + 1) + 'select2" name="category_id[' + (i + 1) + ']" data-placeholder="Pilih" style="width: 100%">{!! $product !!}</select>';
        var html = '';
        html += '<tr>';
        html += '<td><button type="button" name="remove" class="btn btn-sm btn-circle btn-outline-danger remove" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
        html += '<td class="nomor">' + (i + 1) + '</td>';
        html += '<td>' + htmlDropdown + '</td>';
        html += '<td width="10%"><select class="form-control producttype" name="producttype[' + (i + 1) + ']" data-placeholder="Pilih" style="width: 100%"><option></option><option value="amount">Komisi</option><option value="percent">Persentase</option><option value="point">Point</option></select></td>';
        html += '<td width="10%"><input type="number" name="productvalue[' + (i + 1) + ']" class="form-control productvalue"/></td>';

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

    $(document).on('click', '.add3', function() {
        var html3 = '';
        html3 += '<tr>';
        html3 += '<td><button type="button" name="remove3" class="btn btn-sm btn-circle btn-outline-danger remove3" title="Hapus Item"><i class="fa fa-times"></i></button></td>';
        html3 += '<td class="nomor3">' + (k + 1) + '</td>';
        html3 += '<td><select class="form-control service" id="service' + (k + 1) + '" name="service[' + (k + 1) + ']" data-placeholder="Pilih" style="width: 100%">{!! $service !!}</select></td>';
        html3 += '<td width="10%"><select class="form-control servicetype" name="servicetype[' + (k + 1) + ']" data-placeholder="Pilih" style="width: 100%"><option></option><option value="amount">Komisi</option><option value="percent">Persentase</option><option value="point">Point</option></select></td>';
        html3 += '<td width="10%"><input type="text" name="serviceqty[' + (k + 1) + ']" class="form-control serviceqty" /></td>';

        html3 += '</tr>';

        $('#tab_logic3 > tbody').append(html3);

        $('#service' + (k + 1) + '').select2({
            placeholder: "Pilih"
        });

        k++;
        updateIds3();

    });

    $(document).on('click', '.remove3', function() {
        $(this).closest('tr').remove();
        updateIds3();
    });

    function updateIds() {
        $('#tab_logic tbody tr').each(function(i, element) {
            var html = $(this).html();
            if (html != '') {
                $(this).find('.nomor').text((i + 1));
                $(this).find('.product').attr('name', 'product[' + (i + 1) + ']');
                $(this).find('.productvalue').attr('name', 'productvalue[' + (i + 1) + ']');
                $(this).find('.producttype').attr('name', 'producttype[' + (i + 1) + ']');
            }
        });

        validator.resetForm();
    }
    updateIds();

    function updateIds3() {
        $('#tab_logic3 tbody tr').each(function(k, element) {
            var html3 = $(this).html();
            if (html3 != '') {
                $(this).find('.nomor3').text((k + 1));
                $(this).find('.service').attr('name', 'service[' + (k + 1) + ']');
                $(this).find('.serviceqty').attr('name', 'serviceqty[' + (k + 1) + ']');
                $(this).find('.servicetype').attr('name', 'servicetype[' + (k + 1) + ']');
            }
        });

        validator.resetForm();
    }
    updateIds3();
});
</script>

@endsection
