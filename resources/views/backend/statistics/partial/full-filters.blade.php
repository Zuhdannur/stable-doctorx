<div class="block font-size-sm" id="my-block">
    <form class="form-vertical" method="post" action="" id="myForm" name="myForm" autocomplete="off" data-parsley-validate>
        <div class="block-content">
            <div class="row">
                @method('POST')
                @csrf
                <div class="col">
                    {{-- Filter --}}
                    <div class="form-group row">
                        <label for="filters" class="col-md-2 from-label">Filter</label>
                        <div class="col-md-4">
                            <select name="filters" id="filters" class="form-control" width="100%">
                                <option value="1">All</option>
                                <option value="2">Hari Ini</option>
                                <option value="3">Kemarin</option>
                                <option value="4">Pekan Ini</option>
                                <option value="5">Pekan Lalu</option>
                                <option value="6">Bulan Ini</option>
                                <option value="7">Bulan Lalu</option>
                                <option value="8">Tahun Ini</option>
                                <option value="9">Tahun Lalu</option>
                                <option value="10">Rentang Tanggal</option>
                            </select>
                        </div>
                    </div>

                    {{-- Date Range --}}
                    <div class="form-group row" id="date-range" style="display: none;">
                        <label for="startdate" class="col-md-2">Tanggal</label>
                        <div class="col-md-4">
                            <div class="input-daterange input-group" data-date-format="{{ setting()->get('date_format_js') }}" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control datepicker" id="date_1" name="date_1" placeholder="Dari" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($date['date_1']) ? $date['date_1'] : null }}">
                                <div class="input-group-prepend input-group-append">
                                    <span class="input-group-text font-w600">s/d</span>
                                </div>
                                <input type="text" class="form-control datepicker" id="date_2" name="date_2" placeholder="Hingga" data-week-start="1" data-autoclose="true" data-today-highlight="true" autocomplete="off" value="{{ isset($date['date_2']) ? $date['date_2'] : null }}">
                            </div>
                        </div>
                    </div>

                    {{-- Form Button --}}
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="text-right">
                                <button type="submit" class="btn btn-sm btn-success">
                                    <i class="fa fa-search"></i> Tampilkan
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
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

        $('#filters').select2({
            'placeholder' : "Pilih"
        })

        $('#filters').val({{ $filter }}).trigger('change');
        
        $('#filters').on('change', function() {
            if($(this).val() == 10){
                $( "#date-range" ).slideDown('slow');
            }else{
                $('#date-range').hide('slow');
            }
        })
    });
</script>