<ul class="list list-timeline pull-t">
    @if(isset($patient->timeline))
        @foreach($patient->appointment as $key => $appointment)
            @if(isset($appointment->prescription->timeline))
            @php $timeline = $appointment->prescription->timeline @endphp
                <!-- Photo Notification -->
                <li>
                    <div class="list-timeline-time">{{ $timeline->created_at->diffForHumans() }}</div>
                    <i class="list-timeline-icon fa fa fa-user-md bg-elegance"></i>
                    <div class="list-timeline-content">
                        <p class="font-w600">{{ $timeline->title }}, Tanggal {{ timezone()->convertToLocal($timeline->timeline_date, 'date') }}</p>
                        <p>{{ $timeline->description }}</p>
                        
                        @if(isset($timeline->files))
                        <div class="row items-push js-gallery img-fluid-100">
                            @foreach($timeline->files as $file)
                            <div class="col-sm-6 col-xl-3">
                                <a class="img-link img-link-zoom-in img-lightbox" href="{{ url($file->document ?? '#') }}">
                                    <img class="img-fluid" src="{{ url($file->document ?? '#') }}" alt="">
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </li>
                <!-- END Photos Notification -->
            @endif
        @endforeach
    @endif

</ul>

<script>
$(function() {
    Codebase.helpers('magnific-popup');

    $(document.body).on('click', '.loadModal', function(e) {
        var loadUrl = $(this).data('href'),
            cacheResult = $(this).data('cache') === 'on';

        $('.modal').remove();
        $('.modal-backdrop').remove();

        $.ajax({
            url: loadUrl,
            data: {},
            localCache: cacheResult,
            dataType: 'html',
            beforeSend: function(xhr) {
                Codebase.blocks('#my-block', 'state_loading');
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

                Codebase.blocks('#my-block', 'state_normal');
            },
            success: function(data) {
                $('body').append(data);

                var $modal = $('.modal');

                $modal.modal({
                    'backdrop': 'static'
                });

                $modal.modal('show');

                Codebase.blocks('#my-block', 'state_normal');
            }
        });

        e.preventDefault();
    });
});
</script>