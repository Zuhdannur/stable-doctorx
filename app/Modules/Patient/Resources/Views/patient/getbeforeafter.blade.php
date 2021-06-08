            <!-- END Invoice Info -->
            <div class="row clearfix">
                <div class="col-md-6">
				    <div class="block block-rounded" id="before">
				        <div class="block-header block-header-default">
				            <h3 class="block-title">Before</h3>
				        </div>
				        <div class="block-content">
				        	<div class="row items-push" id="imageGrid">

                    		</div>
				        	<div class="row items-push js-gallery img-fluid-100 js-gallery-enabled">
							    <div class="col-md-8 animated fadeIn" id="beforeImage">

							    </div>
							</div>
				            <div class="row items-push tag-before">
				            	@if(isset($patient->beforeafter))
					            	@foreach($patient->beforeafter->where('type', 'before') as $item)
								    <div class="col-md-6 col-lg-4 col-xl-3 animated fadeIn">
									    <div class="options-container fx-item-zoom-in fx-overlay-zoom-out">
									        <img class="img-fluid options-item" data-zoom-image="{{ URL::asset($item->image) }}"/ src="{{ URL::asset($item->image) }}" alt="">
									        <div class="options-overlay bg-black-op">
									            <div class="options-overlay-content">
									                <h6 class="h6 text-white mb-5">Image</h6>
									                <h6 class="h6 text-white-op mb-15">More Details</h6>
									                <a class="btn btn-sm btn-rounded btn-alt-primary min-width-75 viewBefore" data-pict="{{ URL::asset($item->image) }}" href="javascript:void(0)">
									                    <i class="fa fa-pencil"></i> Lihat
									                </a>
									                <a class="btn btn-sm btn-rounded btn-alt-danger min-width-75" href="javascript:void(0)">
									                    <i class="fa fa-times"></i> Hapus
									                </a>
									            </div>
									        </div>
									    </div>
									</div>
									@endforeach
								@endif
                                <div class="custom-image col-md-6 col-lg-4 col-xl-3 animated fadeIn">

                                </div>
							</div>
				        </div>
				    </div>
				</div>
                <div class="col-md-6">
				    <div class="block block-rounded" id="after">
				        <div class="block-header block-header-default">
				            <h3 class="block-title">After</h3>
				        </div>
				        <div class="block-content">
				        	<div class="row items-push js-gallery img-fluid-100 js-gallery-enabled">
							    <div class="col-md-8 animated fadeIn" id="afterImage">

							    </div>
							</div>
				            <div class="row items-push js-gallery img-fluid-100 js-gallery-enabled tag-after">
							    @if(isset($patient->beforeafter))
					            	@foreach($patient->beforeafter->where('type', 'after') as $item)
								    <div class="col-md-6 col-lg-4 col-xl-3 animated fadeIn">
									    <div class="options-container fx-item-zoom-in fx-overlay-zoom-out">
									        <img class="img-fluid options-item" src="{{ URL::asset($item->image) }}" alt="">
									        <div class="options-overlay bg-black-op">
									            <div class="options-overlay-content">
									                <h6 class="h6 text-white mb-5">Image</h6>
									                <h6 class="h6 text-white-op mb-15">More Details</h6>
									                <a class="btn btn-sm btn-rounded btn-alt-primary min-width-75 viewAfter" data-pict="{{ URL::asset($item->image) }}" href="javascript:void(0)">
									                    <i class="fa fa-pencil"></i> Lihat
									                </a>
									                <a class="btn btn-sm btn-rounded btn-alt-danger min-width-75" href="javascript:void(0)">
									                    <i class="fa fa-times"></i> Hapus
									                </a>
									            </div>
									        </div>
									    </div>
									</div>
									@endforeach
								@endif
                                    <div class="custom-image col-md-6 col-lg-4 col-xl-3 animated fadeIn">

                                    </div>
							</div>
				        </div>
				    </div>
				</div>
            </div>


<script src="{{ URL::asset('js/plugins/elevatezoom/jquery.ez-plus.js') }}"></script>
<script type="text/javascript">
jQuery(function () {
	var imageUrlBefore;

	var imageUrlAfter;

	$(document.body).on('click', '.viewBefore', function(e) {
	    imageUrlBefore = $(this).attr('data-pict');
	    var htmlImageBefore = '<a class="img-link-simple img-thumb" href="javascript:void(0);"><img class="photobefore img-fluid" src="'+imageUrlBefore+'" alt="" data-magnify-src="'+imageUrlBefore+'" data-zoom-image="'+imageUrlBefore+'"></a>';

	    $('#beforeImage').html(htmlImageBefore);

	    $('.photobefore').ezPlus({
		    easing: true,
		    scrollZoom: true,
		    tint: true,
    		tintColour: '#F90', tintOpacity: 0.5,
    		zoomWindowPosition: 1
    		/*zoomWindowPosition: 'before',
		    zoomWindowHeight: 200,
		    zoomWindowWidth: 200,
		    borderSize: 0,*/
		});
		// $('.photobefore').magnify();
	});

	$(document.body).on('click', '.viewAfter', function(e) {
	    imageUrlAfter = $(this).attr('data-pict');
	    var htmlImageAfter = '<a class="img-link-simple img-thumb" href="javascript:void(0);"><img class="photoafter img-fluid" src="'+imageUrlAfter+'" alt="" data-magnify-src="'+imageUrlAfter+'"></a>';

	    $('#afterImage').html(htmlImageAfter);

	    $('.photoafter').ezPlus({
		    easing: true,
		    scrollZoom: true,
		    tint: true,
    		tintColour: '#F90', tintOpacity: 0.5,
    		zoomWindowPosition: 11
		});
	    // $('.photoafter').magnify();
	});

    var cacheResult = $(this).data('cache') === 'on';
    $('#patient_id').select2({
        placeholder: "Pilih",
        minimumInputLength: 3,
        allowClear: true
    }).on("select2:select", function (e) {
        var selected = e.params.data;

        if (typeof selected !== "undefined") {
            var loadUrl = '{{ route("admin.patient.beforeafter.get", ":id") }}';
            loadUrl = loadUrl.replace(':id', selected.id);

	        $.ajax({
			    url: loadUrl,
			    data: {},
			    localCache: cacheResult,
			    type: 'GET',
			    dataType: 'html',
			    beforeSend: function(xhr) {
			        Codebase.blocks('.my-block', 'state_loading');
			    },
			    error: function(x, status, error) {
			        if (x.status == 403) {
			            $.alert({
			                title: 'Error',
			                icon: 'fa fa-warning',
			                type: 'red',
			                content: "Error: " + x.status + "",
			            });
			        } else if (x.status === 422) {
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
			            $.alert({
			                title: 'Error',
			                icon: 'fa fa-warning',
			                type: 'red',
			                content: "An error occurred: " + status + "nError: " + error,
			            });
			        }

			        Codebase.blocks('.my-block', 'state_normal');
			    },
			    success: function(data) {

			        Codebase.blocks('.my-block', 'state_normal');
			    }
			});
        }
    });
});
</script>
