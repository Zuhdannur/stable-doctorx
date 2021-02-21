<?php

namespace App\Modules\Accounting\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Helpers\General\RandomColor;
use App\Modules\Accounting\Charts\AccountingChart;
use App\Modules\Accounting\Models\FinanceBiayaTrx;

class BiayaStatistics extends AbstractWidget
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
    protected $config = [];

    public function biaya()
    {
        $biaya = FinanceBiayaTrx::select( \DB::raw('SUM(total) as total'), \DB::raw('DATE_FORMAT(created_at, "%m") as month'))
                    ->whereYear('created_at', \Carbon\Carbon::now()->format('Y'))
                    ->groupBy('month')
                    ->orderBy('month','asc')
                    ->get();

        // mapping data  biaya
        $data = array(
            'Januari' => 0,
            'Februari' => 0,
            'Maret' => 0,
            'April' => 0,
            'Mei' => 0,
            'Juni' => 0,
            'Agustus' => 0,
            'September' => 0,
            'November' => 0,
            'Desember' => 0,
        );

        foreach ($biaya as $value) {
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

        $warna = rcolor()->listColor(count($data));

        $chart = new AccountingChart;
        $chart->labels(array_keys($data));
        $chart->minimalist(false);
        $chart->displayLegend(false);
        $chart->displayAxes(true);
        $chart->dataset('Statistik Pembelian', 'bar', array_values($data))->backgroundColor($warna);
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
        $biaya = $this->biaya();
    
        return view('accounting::widgets.biaya-statistics', compact('biaya'));
    }
}
