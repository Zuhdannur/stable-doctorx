<?php

namespace App\Modules\Product\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Charts\Highcharts;
use App\Helpers\General\RandomColor;

class Statistics extends AbstractWidget
{
    public function placeholder()
    {
        return '<div class="row text-center">
                        <div class="col-12">
                            <div class="block">
                                <div class="block-content">
                                    <p><i class="fa fa-3x fa-cog fa-spin text-info"></i></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="block">
                                <div class="block-content">
                                    <p><i class="fa fa-3x fa-cog fa-spin text-info"></i></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="block">
                                <div class="block-content">
                                    <p><i class="fa fa-3x fa-cog fa-spin text-info"></i></p>
                                </div>
                            </div>
                        </div>
                    </div>';
    }

    /**
     * The number of minutes before cache expires.
     * False means no caching at all.
     *
     * @var int|float|bool
     */
    public $cacheTime = false;

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'start_date' => '',
        'end_date' => '',
    ];

    /*public function treatment()
    {
        $data = [];
        // Circle trough all 12 months
        for ($month = 1; $month <= 12; $month++) {
            // Create a Carbon object from the current year and the current month (equals 2019-01-01 00:00:00)
            $date = \Carbon\Carbon::create(date('Y'), $month);

            // Make a copy of the start date and move to the end of the month (e.g. 2019-01-31 23:59:59)
            $date_end = $date->copy()->endOfMonth();

            $transaksi = \App\Modules\Billing\Models\Billing::where('status', config('billing.invoice_paid'))
                ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
                ->where('invoices.created_at', '>=', $date)
                ->where('invoices.created_at', '<=', $date_end)
                ->where('invoice_details.type', 'service')
                // ->Where('status','!=','Menunggu')
                ->count();
            $data[\Carbon\Carbon::create()->day(1)->month($month)->translatedFormat('F')] = $transaksi;
        }

        $warna = rcolor()->listColor(count($data));

        $chart = new \App\Modules\Product\Charts\Chartjs;
        $chart->labels(array_keys($data));
        $chart->dataset('Total', 'bar', array_values($data))->backgroundColor($warna);

        return $chart;
    }*/
    
    public function treatment()
    {
        $keydata = array();
        $valueData = array();

        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
            ->whereBetween('invoices.created_at',[ $this->config['start_date'], $this->config['end_date']])
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('services', 'services.id', '=', 'invoice_details.product_id')
            ->select('services.name', \DB::raw('count(\'invoice_details.product_id\') as sales_count'))
            // ->where('invoices.created_at', '>=', $date)
            // ->where('invoices.created_at', '<=', $date_end)
            ->where('invoice_details.type', 'service')
            ->groupBy('services.id')
            ->orderBy('sales_count', 'desc')
            ->take(10)
            ->get();
        }else{
            $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('services', 'services.id', '=', 'invoice_details.product_id')
            ->select('services.name', \DB::raw('count(\'invoice_details.product_id\') as sales_count'))
            // ->where('invoices.created_at', '>=', $date)
            // ->where('invoices.created_at', '<=', $date_end)
            ->where('invoice_details.type', 'service')
            ->groupBy('services.id')
            ->orderBy('sales_count', 'desc')
            ->take(10)
            ->get();
        }

        if(!empty($transaksi)){
            foreach ($transaksi as $key => $value) {
                $keydata[] = $value['name'];
                $valueData[] = $value['sales_count'];
            }
        }

        $warna = rcolor()->listColor(count($transaksi));

        $chart = new \App\Modules\Product\Charts\Chartjs;
        $chart->labels($keydata);
        $chart->dataset('Total', 'pie', $valueData)->backgroundColor($warna);

        return $chart;
    }

    public function top10Treatment()
    {
        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
            ->whereBetween('invoices.created_at', [ $this->config['start_date'], $this->config['end_date'] ])
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('services', 'services.id', '=', 'invoice_details.product_id')
            ->select('services.code', 'services.name', \DB::raw('SUM(invoice_details.qty) as quantity_sales_count'), \DB::raw('count(\'invoice_details.product_id\') as sales_count'))
            // ->where('invoices.created_at', '>=', $date)
            // ->where('invoices.created_at', '<=', $date_end)
            ->where('invoice_details.type', 'service')
            ->groupBy('services.id')
            ->orderBy('quantity_sales_count', 'desc')
            ->take(10)
            ->get();
        }else{
            $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('services', 'services.id', '=', 'invoice_details.product_id')
            ->select('services.code', 'services.name', \DB::raw('SUM(invoice_details.qty) as quantity_sales_count'), \DB::raw('count(\'invoice_details.product_id\') as sales_count'))
            // ->where('invoices.created_at', '>=', $date)
            // ->where('invoices.created_at', '<=', $date_end)
            ->where('invoice_details.type', 'service')
            ->groupBy('services.id')
            ->orderBy('quantity_sales_count', 'desc')
            ->take(10)
            ->get();
        }
        
        return $transaksi;
    }

    public function product()
    {
        $keydata = array();
        $valueData = array();

        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
            ->whereBetween('invoices.created_at', [ $this->config['start_date'], $this->config['end_date'] ])
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('products', 'products.id', '=', 'invoice_details.product_id')
            ->select('products.name', \DB::raw('count(\'invoice_details.product_id\') as sales_count'))
            // ->where('invoices.created_at', '>=', $date)
            // ->where('invoices.created_at', '<=', $date_end)
            ->where('invoice_details.type', 'product')
            ->groupBy('products.id')
            ->orderBy('sales_count', 'desc')
            ->take(10)
            ->get();
        } else{
            $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('products', 'products.id', '=', 'invoice_details.product_id')
            ->select('products.name', \DB::raw('count(\'invoice_details.product_id\') as sales_count'))
            // ->where('invoices.created_at', '>=', $date)
            // ->where('invoices.created_at', '<=', $date_end)
            ->where('invoice_details.type', 'product')
            ->groupBy('products.id')
            ->orderBy('sales_count', 'desc')
            ->take(10)
            ->get();
        }

        if(!empty($transaksi)){
            foreach ($transaksi as $key => $value) {
                $keydata[] = $value['name'];
                $valueData[] = $value['sales_count'];
            }
        }

        $warna = rcolor()->listColor(count($transaksi));

        $chart = new \App\Modules\Product\Charts\Chartjs;
        $chart->labels($keydata);
        $chart->dataset('Total', 'pie', $valueData)->backgroundColor($warna);

        return $chart;
    }

    public function top10Product()
    {
        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
            ->whereBetween('invoices.created_at', [ $this->config['start_date'] ,$this->config['end_date'] ])
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('products', 'products.id', '=', 'invoice_details.product_id')
            ->select('products.code', 'products.name', \DB::raw('SUM(invoice_details.qty) as quantity_sales_count'), \DB::raw('count(\'invoice_details.product_id\') as sales_count'))
            // ->where('invoices.created_at', '>=', $date)
            // ->where('invoices.created_at', '<=', $date_end)
            ->where('invoice_details.type', 'product')
            ->groupBy('products.id')
            ->orderBy('quantity_sales_count', 'desc')
            ->take(10)
            ->get();
        } else{
            $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
            ->leftJoin('invoice_details', 'invoices.id', '=', 'invoice_details.invoice_id')
            ->leftJoin('products', 'products.id', '=', 'invoice_details.product_id')
            ->select('products.code', 'products.name', \DB::raw('SUM(invoice_details.qty) as quantity_sales_count'), \DB::raw('count(\'invoice_details.product_id\') as sales_count'))
            // ->where('invoices.created_at', '>=', $date)
            // ->where('invoices.created_at', '<=', $date_end)
            ->where('invoice_details.type', 'product')
            ->groupBy('products.id')
            ->orderBy('quantity_sales_count', 'desc')
            ->take(10)
            ->get();
        }
        
        return $transaksi;
    }

    public function billing()
    {
        $data = [];
        // Circle trough all 12 months
        for ($month = 1; $month <= 12; $month++) {
            // Create a Carbon object from the current year and the current month (equals 2019-01-01 00:00:00)
            $date = \Carbon\Carbon::create(date('Y'), $month);

            // Make a copy of the start date and move to the end of the month (e.g. 2019-01-31 23:59:59)
            $date_end = $date->copy()->endOfMonth();

            $transaksi = \App\Modules\Billing\Models\Billing::where('status', config('billing.invoice_paid'))
                ->where('invoices.created_at', '>=', $date)
                ->where('invoices.created_at', '<=', $date_end)
                // ->Where('status','!=','Menunggu')
                ->get();

            $totalIncome = 0;

            if(!empty($transaksi)){
                foreach ($transaksi as $nilai) {
                    $totalIncome += $nilai->totalprice;
                }
            }

            $data[\Carbon\Carbon::create()->day(1)->month($month)->translatedFormat('F')] = $totalIncome;
        }

        $warna = rcolor()->listColor(count($data));

        $chart = new \App\Modules\Product\Charts\Chartjs;
        $chart->labels(array_keys($data));
        $chart->minimalist(false);
        $chart->displayLegend(true);
        $chart->displayAxes(true);
        $chart->dataset('Total', 'bar', array_values($data))->backgroundColor($warna);
        $chart->options([
            'tooltips' => [
                'borderWidth' => 10,
                'mode' => 'nearest',
                'intersect' => false,                
                'callbacks' => [
                    'label' => $chart->rawObject('rupiahLabel')
                ]
            ],
            'scales' => [
                'yAxes' => [
                    [
                        'ticks' => [
                            'callback' => $chart->rawObject('myCallback')
                        ]
                    ]
                ]
            ]
        ]);

        return $chart;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $treatment = $this->treatment();
        $product = $this->product();
        $billing = $this->billing();
        $top10Product = $this->top10Product();
        $top10Treatment = $this->top10Treatment();
        return view('product::widgets.datastatistics', compact('treatment', 'product', 'billing', 'top10Product', 'top10Treatment'));
    }
}
