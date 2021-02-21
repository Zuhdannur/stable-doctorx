<div class="block font-size-sm" id="my-block">
    <form class="form-vertical" method="post" action="" id="myForm" name="myForm" autocomplete="off" data-parsley-validate>
        <div class="block-content">
            @method('POST')
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row justify-content-center">
                        <div class="col-6 text-center">
                            <label for="startdate">Tanggal</label>
                            <div class="input-daterange input-group" data-date-format="{{ setting()->get('date_format_js') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control datepicker" id="date_1" name="date_1" placeholder="Dari" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($date['date_1']) ? $date['date_1'] : null }}">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">s/d</span>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_2" name="date_2" placeholder="Hingga" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($date['date_2']) ? $date['date_2'] : null }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content block-content-full block-content-sm bg-body-light text-center">
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fa fa-search"></i> Tampilkan
            </button>
        </div>
    </form>
</div>

<script>
    jQuery(function(){ 
        Codebase.helpers(['datepicker', 'notify', 'select2']);

        $(document).on("focusout", ".datepicker", function() {
            $(this).prop('readonly', false);
        });

        $(document).on("focusin", ".datepicker", function() {
            $(this).prop('readonly', true);
        });
    });
</script>