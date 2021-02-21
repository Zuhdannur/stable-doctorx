@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('fees::labels.backend.fees.feetype.management'))

@section('content')
<div class="row">
    <div class="col-xl-3">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
			    <h3 class="block-title">Logo Kotak</h3>
			    <div class="block-options">
			        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="modal" data-target="#modalImage" data-backdrop="static" data-keyboard="false" data-key="logo_square" data-title="Logo Kotak (500px X 500px)">
			            <i class="fa fa-picture-o mr-5"></i>Ubah
			        </button>
			    </div>
			</div>
            <div class="block-content block-content-full">
                <div class="push">
                    <img src="{{ asset(setting()->get('logo_square')) }}" width="150px" class="" alt="">
                </div>
            </div>
        </div>

        <div class="block block-rounded block-bordered" href="javascript:void(0)">
            <div class="block-header block-header-default">
			    <h3 class="block-title">Logo Header</h3>
			    <div class="block-options">
			        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="modal" data-target="#modalImage" data-backdrop="static" data-keyboard="false" data-key="logo_header" data-title="Logo Header (182px X 18px)">
			            <i class="fa fa-picture-o mr-5"></i>Ubah
			        </button>
			    </div>
			</div>
            <div class="block-content block-content-full">
                <div class="push">
                    <img src="{{ asset(setting()->get('logo_header')) }}" width="200px" class="" alt="">
                </div>
            </div>
        </div>

        <div class="block block-rounded block-bordered" href="javascript:void(0)">
            <div class="block-header block-header-default">
			    <h3 class="block-title">Favicon</h3>
			    <div class="block-options">
			        <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="modal" data-target="#modalImage" data-backdrop="static" data-keyboard="false" data-key="favicon" data-title="Favicon (32px X 32px)">
			            <i class="fa fa-picture-o mr-5"></i>Ubah
			        </button>
			    </div>
			</div>
            <div class="block-content block-content-full">
                <div class="push">
                    <img src="{{ asset(setting()->get('favicon')) }}" width="32px" class="" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-9">
        <div class="block block-rounded block-bordered block-fx-shadow">
            <div class="block-header block-header-default">
                <h3 class="block-title">Pengaturan Umum</h3>
                <div class="block-options">
                    <button type="button" class="btn btn-sm btn-alt-primary" data-toggle="modal" data-target="#modalGeneral" data-backdrop="static" data-keyboard="false">
			            <i class="si si-list mr-5"></i>Ubah
			        </button>
                </div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                <table class="table table-hover table-vcenter table-sm">
                    <tbody>
                        <tr>
                            <td>
                                <span class="font-w600">Nama Instansi</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('app_name') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Alamat</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('address') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Nomor Telepon</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('phone') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Email</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('email') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Zona Waktu</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('timezone') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Format Tanggal</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('date_format') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Format Tanggal JS</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('date_format_js') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Format Waktu</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('time_format') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Mata Uang</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('currency') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Simbol Mata Uang</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('currency_symbol') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Copyright Name</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('copyright') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="font-w600">Copyright Link</span>
                            </td>
                            <td class="text-right">
                                <span>{{ setting()->get('copyright_link') }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true" id="modalGeneral">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
        <div class="modal-content">
            <form class="js-validation-bootstrap" method="post" id="formGeneral" autocomplete="off">
                <div class="block block-themed block-transparent mb-0" id="my-block2">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title">Edit Pengaturan Umum</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content">
                        <div class="row">
			                <div class="col-md-12">
			                    <div class="form-group row">
			                        <div class="col-6">
			                            <label>Nama Instansi</label>
			                            <input type="text" class="form-control" id="app_name" name="app_name" value="{{ setting()->get('app_name') }}">
			                        </div>
			                        <div class="col-6">
			                            <label>Nama Pendek Instansi</label>
			                            <input type="text" class="form-control" id="short_name" name="short_name" value="{{ setting()->get('short_name') }}">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-6">
			                            <label>Nomor Telepon</label>
			                            <input type="text" class="form-control" id="phone" name="phone" value="{{ setting()->get('phone') }}">
			                        </div>
			                        <div class="col-6">
			                            <label>Email</label>
			                            <input type="text" class="form-control" id="email" name="email" value="{{ setting()->get('email') }}">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-6">
			                            <label>Alamat</label>
			                            <input type="text" class="form-control" id="address" name="address" value="{{ setting()->get('address') }}">
			                        </div>
			                        <div class="col-6">
			                            <label>Zona Waktu</label>
			                            <br>
			                            {!! \Timezonelist::create('timezone', setting()->get('timezone'), 'id="timezone" class="js-select2 form-control" style="width:100%"') !!}
			                            
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-4">
			                            <label>Format Tanggal</label>
			                            <select id="date_format" name="date_format" class="form-control">
										    <option value="">Pilih</option>
										    <option value="d-m-Y" {{ ( 'd-m-Y' == setting()->get('date_format')) ? 'selected' : '' }}>d-m-Y ({{ date('d-m-Y') }})</option>
										    <option value="d-M-Y" {{ ( 'd-M-Y' == setting()->get('date_format')) ? 'selected' : '' }}>d-M-Y ({{ date('d-M-Y') }})</option>
										    <option value="d/m/Y" {{ ( 'd/m/Y' == setting()->get('date_format')) ? 'selected' : '' }}>d/m/Y ({{ date('d/m/Y') }})</option>
										    <option value="d/M/Y" {{ ( 'd/M/Y' == setting()->get('date_format')) ? 'selected' : '' }}>d/M/Y ({{ date('d/M/Y') }})</option>
										    <option value="d.m.Y" {{ ( 'd.m.Y' == setting()->get('date_format')) ? 'selected' : '' }}>d.m.Y ({{ date('d.m.Y') }})</option>
										    <option value="d.M.Y" {{ ( 'd.M.Y' == setting()->get('date_format')) ? 'selected' : '' }}>d.M.Y ({{ date('d.M.Y') }})</option>
										</select>
			                        </div>
			                        <div class="col-4">
			                            <label>Format Tanggal JS</label>
			                            <select id="date_format_js" name="date_format_js" class="form-control">
										    <option value="">Pilih</option>
										    <option value="dd-mm-yyyy" {{ ( 'dd-mm-yyyy' == setting()->get('date_format_js')) ? 'selected' : '' }}>dd-mm-yyyy</option>
										    <option value="dd/mm/yyyy" {{ ( 'dd/mm/yyyy' == setting()->get('date_format_js')) ? 'selected' : '' }}>dd/mm/yyyy</option>
										    <option value="yyyy-mm-dd" {{ ( 'yyyy-mm-dd' == setting()->get('date_format_js')) ? 'selected' : '' }}>yyyy-mm-dd</option>
										    <option value="yyyy/mm/dd" {{ ( 'yyyy/mm/dd' == setting()->get('date_format_js')) ? 'selected' : '' }}>yyyy/mm/dd</option>
										</select>
			                        </div>
			                        <div class="col-4">
			                            <label>Format Waktu</label>
			                            <select id="time_format" name="time_format" class="form-control" autocomplete="off">
                                			<option value="">Pilih</option>
                                        	<option value="24-hour">24 Jam</option>
                                        	<option value="12-hour" selected="selected">12 Jam</option>
                                		</select>
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <div class="col-6">
			                            <label>Mata Uang</label>
			                            <br>
			                            <select id="currency" name="currency" class="js-select2 form-control" style="width:100%" autocomplete="off">
			                            	<option></option>
			                            	@foreach(currency()->currencies() as $item)
												<option value="{{ $item['code'] }}" {{ ( $item['code'] == setting()->get('currency')) ? 'selected' : '' }}>{{ $item['code'] }} - {{ $item['name'] }}</option>
			                            	@endforeach
                                		</select>
			                        </div>
			                        <div class="col-6">
			                            <label>Simbol Mata Uang</label>
			                            <select id="currency_symbol" name="currency_symbol" class="js-select2 form-control" style="width:100%" autocomplete="off">
			                            	<option></option>
			                            	@foreach(currency()->currencies() as $item)
												<option value="{{ $item['symbol'] }}" {{ ( $item['symbol'] == setting()->get('currency_symbol')) ? 'selected' : '' }}>{{ $item['symbol'] }} - {{ $item['name'] }}</option>
			                            	@endforeach
                                		</select>
			                        </div>
			                    </div>
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label>Copyright Name</label>
                                        <input type="text" class="form-control" id="copyright" name="copyright" value="{{ setting()->get('copyright') }}">
                                    </div>
                                    <div class="col-6">
                                        <label>Copyright Link</label>
                                        <input type="text" class="form-control" id="copyright_link" name="copyright_link" value="{{ setting()->get('copyright_link') }}">
                                    </div>
                                </div>
			                </div>
			            </div>
                    </div>
                
	                <div class="modal-footer">
	                    <button type="button" class="btn btn-alt-secondary" data-dismiss="modal">Tutup</button>
	                    <button type="submit" class="btn btn-alt-success">
	                        <i class="fa fa-check"></i> Simpan Data
	                    </button>
	                </div>
	            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-popout" aria-hidden="true" id="modalImage">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
                <div class="block block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title modal-title">Edit Gambar</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                <i class="si si-close"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row">
			                <div class="col-md-12">
								<form method="post" action="{{ route('admin.setting.general.store.image') }}" enctype="multipart/form-data" class="dropzone" id="dropzone">
								    @csrf
								    <input type="hidden" id="key" name="key">
								</form>
			                </div>

			            </div>
                    </div>
                </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="{{ URL::asset('js/plugins/dropzonejs/dist/dropzone.css') }}">
<script src="{{ URL::asset('js/plugins/dropzonejs/dropzone.min.js') }}"></script>
<script>
jQuery(function () {
    $('.js-select2').select2({
        dropdownParent: $('#modalGeneral'),
        placeholder: "Pilih"
    });
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
                'fee_group_id': {
                    required: true
                },
                'fee_type_id': {
                    required: true
                },
                'due_date': {
                    required: true
                },
                'amount': {
                    required: true,
                    minlength: 5,
                    maxlength: 12,
                    number: true
                },
            },
			submitHandler: function(form) {
			    $.ajax({
			        type: "POST",
			        url: "{{ route('admin.setting.general.store') }}",
			        data: $(form).serialize(),
			        dataType: 'json',
			        beforeSend: function(xhr) {
		                Codebase.blocks('#my-block2', 'state_loading');
		            },
		            error: function(x, status, error) {
					    if (x.status == 403) {
					        $.alert({
					            title: 'Error',
					            icon: 'fa fa-warning',
					            type: 'red',
					            content: "Error: " + x.status + "",
					        });
					    } else {
					        $.alert({
					            title: 'Error',
					            icon: 'fa fa-warning',
					            type: 'red',
					            content: "An error occurred: " + status + "nError: " + error,
					        });
					    }

					    Codebase.blocks('#my-block2', 'state_normal');
					},
			        success: function(result) {
			            if(result.status){
                            $('#modalGeneral').modal('hide');
                            $.notify({
                                message: result.message,
                                type: 'success'
                            });

                            setTimeout(function(){// wait for 5 secs(2)
                                location.reload(); // then reload the page.(3)
                            }, 2000); 
                        }else{
                            $.alert({
                                title: 'Error',
                                icon: 'fa fa-warning',
                                type: 'orange',
                                content: result.message,
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

$('#modalImage').on('show.bs.modal', function(event) {
    var key = $(event.relatedTarget).attr('data-key');
    var title = $(event.relatedTarget).attr('data-title');
    
    $(this).find('.modal-title').text(title);
    $(this).find('#key').val(key);
});

Dropzone.options.dropzone = {
	uploadMultiple: false,
    maxFilesize: 0.1,
    acceptedFiles: ".png",
    dictDefaultMessage: "Tarik dan letakkan file di sini untuk mengunggah atau klik untuk memilih file.",
    timeout: 10000,
    success: function(file, response) {
        console.log(response);
        $('#modalImage').modal('hide');
        $.notify({
		    message: response.message,
		    type: 'success'
		});

		setTimeout(function() { // wait for 5 secs(2)
		    location.reload(); // then reload the page.(3)
		}, 2000);
    }
};
</script>
@endsection
