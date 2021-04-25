<?php

namespace App\Modules\Patient\Widgets\Statistics;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Models\Patient;
use App\Modules\Patient\Models\PatientMediaInfo;
use App\Modules\Patient\Charts\PatientSource;
use App\Helpers\General\RandomColor;

class Demografi extends AbstractWidget
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
        'filter' => '1', //1-10
        'start_date' => '',
        'end_date' => ''
    ];

    public function work()
    {
        switch ($this->config['filter']) {
            case '1': //all
                $data = Patient::get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            case '2': //today
                $data = Patient::whereDate('created_at', \Carbon\Carbon::now() )->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '3': //Yesterday
                $data = Patient::whereDate('created_at', \Carbon\Carbon::now()->subDay(1) )->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '4': //This Week
                $data = Patient::whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()] )->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now();
                $data = Patient::whereBetween('created_at', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()] )->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '6': //This Month
                $data = Patient::whereMonth('created_at', \Carbon\Carbon::now())->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '7': //Last Month
                $data = Patient::whereMonth('created_at', \Carbon\Carbon::now()->subMonth(1))->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '8': //This Year
                $data = Patient::whereYear('created_at', \Carbon\Carbon::now())->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            case '9': //Last Year
                $data = Patient::whereYear('created_at', \Carbon\Carbon::now()->subYear(1))->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                $data = Patient::with('district')->whereBetween('created_at',[ $this->config['start_date'], $this->config['end_date'] ] )
                    ->get()
                    ->groupBy('work.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
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

    public function age()
    {
        switch ($this->config['filter']) {
            case '1': //all
                $data = Patient::get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            case '2': //today
                $data = Patient::whereDate('created_at', \Carbon\Carbon::now() )->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '3': //Yesterday
                $data = Patient::whereDate('created_at', \Carbon\Carbon::now()->subDay(1) )->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '4': //This Week
                $data = Patient::whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()] )->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now();
                $data = Patient::whereBetween('created_at', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()] )->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '6': //This Month
                $data = Patient::whereMonth('created_at', \Carbon\Carbon::now())->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '7': //Last Month
                $data = Patient::whereMonth('created_at', \Carbon\Carbon::now()->subMonth(1))->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '8': //This Year
                $data = Patient::whereYear('created_at', \Carbon\Carbon::now())->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            case '9': //Last Year
                $data = Patient::whereYear('created_at', \Carbon\Carbon::now()->subYear(1))->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                $data = Patient::with('district')->whereBetween('created_at',[ $this->config['start_date'], $this->config['end_date'] ] )
                    ->get()
                    ->groupBy('age_range')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
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
        $chart->doughnut(50);
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

    public function district()
    {
        switch ($this->config['filter']) {
            case '1': //all
                $data = Patient::get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            case '2': //today
                $data = Patient::whereDate('created_at', \Carbon\Carbon::now() )->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '3': //Yesterday
                $data = Patient::whereDate('created_at', \Carbon\Carbon::now()->subDay(1) )->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '4': //This Week
                $data = Patient::whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()] )->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now();
                $data = Patient::whereBetween('created_at', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()] )->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '6': //This Month
                $data = Patient::whereMonth('created_at', \Carbon\Carbon::now())->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '7': //Last Month
                $data = Patient::whereMonth('created_at', \Carbon\Carbon::now()->subMonth(1))->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '8': //This Year
                $data = Patient::whereYear('created_at', \Carbon\Carbon::now())->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            case '9': //Last Year
                $data = Patient::whereYear('created_at', \Carbon\Carbon::now()->subYear(1))->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                $data = Patient::with('district')->whereBetween('created_at',[ $this->config['start_date'], $this->config['end_date'] ] )
                    ->get()
                    ->groupBy('district.name')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
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

    public function gender()
    {
        switch ($this->config['filter']) {
            case '1': //all
                $data = Patient::get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            case '2': //today
                $data = Patient::whereDate('created_at', \Carbon\Carbon::now() )->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '3': //Yesterday
                $data = Patient::whereDate('created_at', \Carbon\Carbon::now()->subDay(1) )->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '4': //This Week
                $data = Patient::whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()] )->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now();
                $data = Patient::whereBetween('created_at', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()] )->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '6': //This Month
                $data = Patient::whereMonth('created_at', \Carbon\Carbon::now())->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '7': //Last Month
                $data = Patient::whereMonth('created_at', \Carbon\Carbon::now()->subMonth(1))->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });

                break;
            case '8': //This Year
                $data = Patient::whereYear('created_at', \Carbon\Carbon::now())->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            case '9': //Last Year
                $data = Patient::whereYear('created_at', \Carbon\Carbon::now()->subYear(1))->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                $data = Patient::with('district')->whereBetween('created_at',[ $this->config['start_date'], $this->config['end_date'] ] )
                    ->get()
                    ->groupBy('gender')
                    ->map(function ($item) {
                        // Return the number of persons with that age
                        return count($item);
                    });
                break;
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
        $chart->doughnut(50);
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

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $work = $this->work();
        $age = $this->age();
        $gender = $this->gender();
        $district = $this->district();

        return view('patient::widgets.statistics.demografi', compact('work', 'age', 'gender', 'district'));
    }
}
