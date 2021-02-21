<?php

namespace App\Modules\Accounting\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Helpers\General\RandomColor;
use App\Modules\Patient\Charts\Highcharts;
use App\Modules\Accounting\Models\FinancePurchase;

class PemasukanStatistics extends AbstractWidget
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
    protected $config = [ ];

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

        $dataPurchasing = $this->_purchasingData();

        $warna = rcolor()->listColor(1);
        $warnaPurchasing = rcolor()->listColor(1);

        $chart = new \App\Modules\Product\Charts\Chartjs;
        $chart->labels(array_keys($data));
        $chart->minimalist(false);
        $chart->displayLegend(true);
        $chart->displayAxes(true);
        $chart->dataset('Pemasukan (invoice)', 'bar', array_values($data))->backgroundColor('#798cf3');
        $chart->dataset('Pembelian', 'bar', array_values($dataPurchasing))->backgroundColor('#f37994');
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

    private function _purchasingData()
    {
        $purchase = FinancePurchase::select( \DB::raw('SUM(total) as total'), \DB::raw('DATE_FORMAT(created_at, "%m") as month'))
            ->whereYear('created_at', \Carbon\Carbon::now()->format('Y'))
            ->groupBy('month')
            ->orderBy('month','asc')
            ->get();

        
        // mapping data  purchase
        $data = array(
            'Januari' => 0,
            'Februari' => 0,
            'Maret' => 0,
            'April' => 0,
            'Mei' => 0,
            'Juni' => 0,
            'Juli' => 0,
            'Agustus' => 0,
            'September' => 0,
            'Oktober' => 0,
            'November' => 0,
            'Desember' => 0,
        );

        foreach ($purchase as $value) {
            switch ($value->month) {
                case '01':
                    $data['Januari'] = $value->total;
                    break;
                case '02':
                    $data['Februari'] = $value->total;
                    break;
                case '3':
                    $data['Maret'] = $value->total;
                    break;
                case '04':
                    $data['April'] = $value->total;
                    break;
                case '05':
                    $data['Mei'] = $value->total;
                    break;
                case '06':
                    $data['Juni'] = $value->total;
                    break;
                case '07':
                    $data['Juli'] = $value->total;
                    break;
                case '08':
                    $data['Agustus'] = $value->total;
                    break;
                case '09':
                    $data['September'] = $value->total;
                    break;
                case '10':
                    $data['Oktober'] = $value->total;
                    break;
                case '11':
                    $data['November'] = $value->total;
                    break;
                case '12':
                    $data['Desember'] = $value->total;
                    break;
            }    
        }
        return $data;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $billing = $this->billing();

        return view('accounting::widgets.pemasukan-statistics', compact('billing'));
    }
}
