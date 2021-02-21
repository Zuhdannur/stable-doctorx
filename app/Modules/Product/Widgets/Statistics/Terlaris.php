<?php

namespace App\Modules\Product\Widgets\Statistics;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Charts\Highcharts;
use App\Helpers\General\RandomColor;

class Terlaris extends AbstractWidget
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
        'filter' => '1', //1-10
        'start_date' => '',
        'end_date' => '',
    ];

    public function treatment()
    {
        $keydata = array();
        $valueData = array();

        switch ($this->config['filter']) {
            case '1': //all
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
                break;
            case '2': //today
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereDate('invoices.created_at',  \Carbon\Carbon::now())
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
                break;
            case '3': //Yesterday
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereDate('invoices.created_at',  \Carbon\Carbon::now()->subDay(1))
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
                break;
            case '4': //This Week
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereBetween('invoices.created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])
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
                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now(); 

                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereBetween('invoices.created_at',[$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()])
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
                break;
            case '6': //This Month
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereMonth('invoices.created_at',\Carbon\Carbon::now())
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
                break;
            case '7': //Last Month
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereMonth('invoices.created_at',\Carbon\Carbon::now()->subMonth(1))
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
                break;
            case '8': //This Year
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereYear('invoices.created_at',\Carbon\Carbon::now())
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
                break;
            case '9': //Last Year
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                        ->whereYear('invoices.created_at',\Carbon\Carbon::now()->subYear(1))
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
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
        
                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
        
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

                break;
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
        switch ($this->config['filter']) {
            case '1': //all
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
                break;
            case '2': //today
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereDate('invoices.created_at',\Carbon\Carbon::now())
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
                break;
            case '3': //Yesterday
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereDate('invoices.created_at',\Carbon\Carbon::now()->subDay(1))
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
                break;
            case '4': //This Week
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereBetween('invoices.created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])
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
                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now(); 

                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereBetween('invoices.created_at', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()])
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
                break;
            case '6': //This Month
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereMonth('invoices.created_at', \Carbon\Carbon::now())
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
                break;
            case '7': //Last Month
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereMonth('invoices.created_at', \Carbon\Carbon::now()->subMonth(1))
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
                break;
            case '8': //This Year
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereYear('invoices.created_at', \Carbon\Carbon::now())
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
                break;
            case '9': //Last Year
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                        ->whereYear('invoices.created_at', \Carbon\Carbon::now()->subYear(1))
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
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
        
                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
        
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

                break;
        }
        
        return $transaksi;
    }

    public function product()
    {
        $keydata = array();
        $valueData = array();

        switch ($this->config['filter']) {
            case '1': //all
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
                break;
            case '2': //today
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereDate('invoices.created_at', \Carbon\Carbon::now())
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
                break;
            case '3': //Yesterday
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereDate('invoices.created_at', \Carbon\Carbon::now()->subDay(1))
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
                break;
            case '4': //This Week
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereBetween('invoices.created_at',  [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])
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
                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now(); 
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereBetween('invoices.created_at', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()])
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
                break;
            case '6': //This Month
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereMonth('invoices.created_at', \Carbon\Carbon::now())
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
                break;
            case '7': //Last Month
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereMonth('invoices.created_at', \Carbon\Carbon::now()->subMonth(1))
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
                break;
            case '8': //This Year
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereYear('invoices.created_at', \Carbon\Carbon::now())
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
                break;
            case '9': //Last Year
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereYear('invoices.created_at', \Carbon\Carbon::now()->subYear(1))
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
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
        
                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
        
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

                break;
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
        switch ($this->config['filter']) {
            case '1': //all
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
                break;
            case '2': //today
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereDate('invoices.created_at',  \Carbon\Carbon::now())
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
                break;
            case '3': //Yesterday
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereDate('invoices.created_at',  \Carbon\Carbon::now()->subDay(1))
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
                break;
            case '4': //This Week
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereBetween('invoices.created_at',  [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()])
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
                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now(); 
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereBetween('invoices.created_at',  [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()])
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
                break;
            case '6': //This Month
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereMonth('invoices.created_at', \Carbon\Carbon::now())
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
                break;
            case '7': //Last Month
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereMonth('invoices.created_at', \Carbon\Carbon::now()->subMonth(1))
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
                break;
            case '8': //This Year
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereYear('invoices.created_at', \Carbon\Carbon::now())
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
                break;
            case '9': //Last Year
                $transaksi = \App\Modules\Billing\Models\Billing::where('invoices.status', config('billing.invoice_paid'))
                    ->whereYear('invoices.created_at', \Carbon\Carbon::now()->subYear(1))
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
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
        
                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
        
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

                break;
        }
        
        return $transaksi;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $treatment = $this->treatment();
        $product = $this->product();
        $top10Product = $this->top10Product();
        $top10Treatment = $this->top10Treatment();
        return view('product::widgets.statistics.product-terlaris', compact('treatment', 'product', 'top10Product', 'top10Treatment'));
    }
}
