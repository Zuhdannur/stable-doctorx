<?php

namespace App\Modules\Patient\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Models\Patient;
use App\Modules\Patient\Models\PatientMediaInfo;
use App\Modules\Patient\Charts\PatientSource;
use App\Modules\Patient\Charts\PatientInfo;
use App\Helpers\General\RandomColor;

class Statistics extends AbstractWidget
{
    public function placeholder()
    {
        return '<div class="row text-center">
                        <div class="col-4">
                            <div class="block">
                                <div class="block-content">
                                    <p><i class="fa fa-3x fa-cog fa-spin text-info"></i></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="block">
                                <div class="block-content">
                                    <p><i class="fa fa-3x fa-cog fa-spin text-info"></i></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
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
    public $cacheTime = 3;

    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'start_date' => '',
        'end_date' => '',
    ];

    public function info()
    {
        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->with('info')->whereBetween('created_at',[$this->config['start_date'], $this->config['end_date']])->get()
                ->groupBy('info.name')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }else{
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->with('info')->get()
                ->groupBy('info.name')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }

        $colorSet = array(
            '#1abc9c',
            '#2ecc71',
            '#3498db',
            '#9b59b6',
            '#34495e',
            '#f1c40f',
            '#e67e22',
            '#e74c3c',
            '#ecf0f1',
            '#95a5a6',
            '#45e1f1'
        );
        // $color
        $warna = rcolor()->listColor(count($data));
        $chart = new PatientSource;
        $chart->labels($data->keys());
        // $chart->title('siapp');
        // $chart->doughnut(50);
        $chart->minimalist(false);
        $chart->displayLegend(true);
        $chart->displayAxes(true);
        $chart->dataset('Total', 'pie', $data->values())->color($warna);
        $chart->options([
            'plotOptions' => [
                'pie'=> [
                    'allowPointSelect'=> true,
                    'cursor'=> 'pointer',
                    'dataLabels'=> [
                        'enabled'=> true,
                        'format'=> '<b>{point.name}</b>: {point.percentage:.1f} %',
                        'connectorColor'=> 'silver'
                    ],
                    'showInLegend'=> false
                ]
            ],
        ]);

        return $chart;
    }

    public function work()
    {
        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->with('work')->whereBetween('created_at', [ $this->config['start_date'], $this->config['end_date'] ] )
                ->get()
                ->groupBy('work.name')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }else{
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->with('work')->get()
                ->groupBy('work.name')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }

        $colorSet = array(
            '#1abc9c',
            '#2ecc71',
            '#3498db',
            '#9b59b6',
            '#34495e',
            '#f1c40f',
            '#e67e22',
            '#e74c3c',
            '#ecf0f1',
            '#95a5a6',
            '#45e1f1'
        );
        // $color
        $warna = rcolor()->listColor(count($data));
        $chart = new PatientSource;
        $chart->labels($data->keys());
        // $chart->title('siapp');
        // $chart->doughnut(50);
        $chart->minimalist(false);
        $chart->displayLegend(true);
        $chart->displayAxes(true);
        $chart->dataset('Total', 'pie', $data->values())->color($warna);
        $chart->options([
            'plotOptions' => [
                'pie'=> [
                    'allowPointSelect'=> true,
                    'cursor'=> 'pointer',
                    'dataLabels'=> [
                        'enabled'=> true,
                        'format'=> '<b>{point.name} tahun</b>: {point.percentage:.1f} %',
                        'connectorColor'=> 'silver'
                    ],
                    'showInLegend'=> false
                ]
            ],
        ]);

        return $chart;
    }

/* public function work()
    {
        $data = Patient::with('work')->get()
            ->groupBy('work.name')
            ->map(function ($item) {
                // Return the number of persons with that age
                return count($item);
            });

        $colorSet = array(
            '#1abc9c',
            '#2ecc71',
            '#3498db',
            '#9b59b6',
            '#34495e',
            '#f1c40f',
            '#e67e22',
            '#e74c3c',
            '#ecf0f1',
            '#95a5a6',
            '#45e1f1'
        );
        $warna = rcolor()->listColor(count($data));

        $chart = new PatientInfo;
        $chart->labels($data->keys());
        $chart->dataset('Total', 'bar', $data->values())->backgroundColor($warna);

        return $chart;
    }
*/
    public function age()
    {
        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->orderBy('age', 'DESC')->whereBetween('created_at', [ $this->config['start_date'], $this->config['end_date'] ])
                ->get()
                ->groupBy('age_range')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }else{
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->orderBy('age', 'DESC')->get()
                ->groupBy('age_range')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }

        $colorSet = array(
            '#1abc9c',
            '#2ecc71',
            '#3498db',
            '#9b59b6',
            '#34495e',
            '#f1c40f',
            '#e67e22',
            '#e74c3c',
            '#ecf0f1',
            '#95a5a6',
            '#45e1f1'
        );
        // $color
        $warna = rcolor()->listColor(count($data));
        $chart = new PatientSource;
        $chart->labels($data->keys());
        // $chart->title('siapp');
        // $chart->doughnut(50);
        $chart->minimalist(false);
        $chart->displayLegend(true);
        $chart->displayAxes(true);
        $chart->dataset('Total', 'pie', $data->values())->color($warna);
        $chart->options([
            'plotOptions' => [
                'pie'=> [
                    'allowPointSelect'=> true,
                    'cursor'=> 'pointer',
                    'dataLabels'=> [
                        'enabled'=> true,
                        'format'=> '<b>{point.name} tahun</b>: {point.percentage:.1f} %',
                        'connectorColor'=> 'silver'
                    ],
                    'showInLegend'=> false
                ]
            ],
        ]);

        return $chart;
    }

    public function district()
    {
        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->with('district')->whereBetween('created_at',[ $this->config['start_date'], $this->config['end_date'] ] )
                ->get()
                ->groupBy('district.name')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }else{
            $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->with('district')->get()
                ->groupBy('district.name')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }

        $colorSet = array(
            '#1abc9c',
            '#2ecc71',
            '#3498db',
            '#9b59b6',
            '#34495e',
            '#f1c40f',
            '#e67e22',
            '#e74c3c',
            '#ecf0f1',
            '#95a5a6',
            '#45e1f1'
        );
        // $color
        $warna = rcolor()->listColor(count($data));
        $chart = new PatientSource;
        $chart->labels($data->keys());
        // $chart->title('siapp');
        // $chart->doughnut(50);
        $chart->minimalist(false);
        $chart->displayLegend(true);
        $chart->displayAxes(true);
        $chart->dataset('Total', 'pie', $data->values())->color($warna);
        $chart->options([
            'plotOptions' => [
                'pie'=> [
                    'allowPointSelect'=> true,
                    'cursor'=> 'pointer',
                    'dataLabels'=> [
                        'enabled'=> true,
                        'format'=> '<b>{point.name}</b>: {point.percentage:.1f} %',
                        'connectorColor'=> 'silver'
                    ],
                    'showInLegend'=> false
                ]
            ],
        ]);

        return $chart;
    }

    public function media()
    {
        if(!empty($this->config['start_date']) && !empty($this->config['end_date'])){
            $data = PatientMediaInfo::whereHas('patient',function ($query) {
                return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
            })->with(['medianame', 'patient'])
                ->whereHas('patient', function($q){
                    return $q->whereBetween('created_at', [ $this->config['start_date'], $this->config['end_date'] ]);
                })
                ->get()
                ->groupBy('medianame.name')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }else{
            $data = PatientMediaInfo::whereHas('patient',function ($query) {
                return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
            })->with('medianame')->get()
                ->groupBy('medianame.name')
                ->map(function ($item) {
                    // Return the number of persons with that age
                    return count($item);
                });
        }

        $colorSet = array(
            '#1abc9c',
            '#2ecc71',
            '#3498db',
            '#9b59b6',
            '#34495e',
            '#f1c40f',
            '#e67e22',
            '#e74c3c',
            '#ecf0f1',
            '#95a5a6',
            '#45e1f1'
        );
        // $color
        $warna = rcolor()->listColor(count($data));
        $chart = new PatientSource;
        $chart->labels($data->keys());
        // $chart->title('siapp');
        // $chart->doughnut(50);
        $chart->minimalist(false);
        $chart->displayLegend(true);
        $chart->displayAxes(true);
        $chart->dataset('Total', 'pie', $data->values())->color($warna);
        $chart->options([
            'plotOptions' => [
                'pie'=> [
                    'allowPointSelect'=> true,
                    'cursor'=> 'pointer',
                    'dataLabels'=> [
                        'enabled'=> true,
                        'format'=> '<b>{point.name}</b>: {point.percentage:.1f} %',
                        'connectorColor'=> 'silver'
                    ],
                    'showInLegend'=> false
                ]
            ],
            'tooltip' => [
                'pointFormat' => "{series.name}: <b>{point.total} orang</b>",
            ],
        ]);

        return $chart;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $info = $this->info();
        $work = $this->work();
        $age = $this->age();
        $district = $this->district();
        $media = $this->media();

        return view('patient::widgets.statistics', compact('info', 'work', 'age', 'district', 'media'));
    }
}
