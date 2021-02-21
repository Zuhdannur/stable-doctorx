<div class="row"> 
    <div class="col-sm-6">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Statistik Treatment</h3>
                <div class="block-options">
                	<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
            	</div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    {!! $treatment->container() !!}
                </div>

                <table class="table table-vcenter table-borderless table-striped">
				    <thead>
				        <tr>
				            <th class="d-none d-sm-table-cell" style="width: 100px;">ID</th>
				            <th>Produk</th>
				            <th class="text-center">Terjual</th>
				        </tr>
				    </thead>
				    <tbody>
				    	@foreach($top10Treatment as $keyt => $treatmentTop)
				        <tr>
				            <td class="d-none d-sm-table-cell">
				                <a class="font-w600" href="javascript:void(0)">{{ ($keyt + 1) }}</a>
				            </td>
				            <td>
				                <a href="javascript:void(0)">{{ $treatmentTop->code }} - {{ $treatmentTop->name }}</a>
				            </td>
				            <td class="text-center">
				                <a class="text-gray-dark" href="javascript:void(0)">{{ $treatmentTop->quantity_sales_count }}</a>
				            </td>
				        </tr>
				        @endforeach
				    </tbody>
				</table>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="block block-rounded block-bordered">
            <div class="block-header block-header-default">
                <h3 class="block-title">Statistik Produk Terjual</h3>
                <div class="block-options">
                	<button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
            	</div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    {!! $product->container() !!}
                </div>

                <table class="table table-vcenter table-borderless table-striped">
				    <thead>
				        <tr>
				            <th class="d-none d-sm-table-cell" style="width: 100px;">ID</th>
				            <th>Produk</th>
				            <th class="text-center">Terjual</th>
				        </tr>
				    </thead>
				    <tbody>
				    	@foreach($top10Product as $keyp => $productTop)
				        <tr>
				            <td class="d-none d-sm-table-cell">
				                <a class="font-w600" href="javascript:void(0)">{{ ($keyp + 1) }}</a>
				            </td>
				            <td>
				                <a href="javascript:void(0)">{{ $productTop->code }} - {{ $productTop->name }}</a>
				            </td>
				            <td class="text-center">
				                <a class="text-gray-dark" href="be_pages_ecom_orders.html">{{ $productTop->quantity_sales_count }}</a>
				            </td>
				        </tr>
				        @endforeach
				    </tbody>
				</table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	formatRupiah = function (bilangan, prefix)
	{
		var pre = '';
		if(bilangan.toString().indexOf("-") > -1){
			pre = "-";
		}

		var number_string = bilangan.toString().replace(/[^,\d]/g, ''),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{1,3}/gi);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? pre + rupiah : (rupiah ? prefix + pre +rupiah : '');
	}

	function myCallback(value, index, values) {
		if(value > 1)
	    	return formatRupiah(value);
	    else
	    	return value;
	}

	function rupiahLabel(tooltipItems, data) {
            // return '{{ setting()->get('currency_symbol') }}' + formatRupiah(tooltipItems.yLabel);
            return formatRupiah(tooltipItems.yLabel);
	}
</script>
{!! $treatment->script() !!}
{!! $product->script() !!}