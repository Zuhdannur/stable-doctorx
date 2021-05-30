<?php

namespace App\Widgets;

use App\Modules\Patient\Charts\PatientPeakTimeChart;
use App\Modules\Patient\Models\Appointment;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Collection;

class StatisticPatient extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'filter' => '1', // 1-10
        'start_date' => '',
        'end_date' => ''
    ];

    protected $label;

    protected function _init() {
        $baseQuery = Appointment::where('id_klinik',auth()->user()->id_klinik);
        switch ($this->config['filter']) {
            case '1': //all
                break;
            case '2': //today
                $baseQuery = $baseQuery->whereDate('date', \Carbon\Carbon::now() );
                break;
            case '3': //Yesterday
                $baseQuery = $baseQuery->whereDate('date', \Carbon\Carbon::now()->subDay(1) );
                break;
            case '4': //This Week
                $baseQuery = $baseQuery->whereBetween('date', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()] );
                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now();
                $baseQuery = $baseQuery->whereBetween('date', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()] );
                break;
            case '6': //This Month
                $baseQuery = $baseQuery->whereMonth('date', \Carbon\Carbon::now());
                break;
            case '7': //Last Month
                $baseQuery = $baseQuery->whereMonth('date', \Carbon\Carbon::now()->subMonth(1));
                break;
            case '8': //This Year
                $baseQuery = $baseQuery->whereYear('date', \Carbon\Carbon::now());
                break;
            case '9': //Last Year
                $baseQuery = $baseQuery->whereYear('date', \Carbon\Carbon::now()->subYear(1));
                break;
            default: //range date
                if($this->config['start_date'] == '' ){
                    $this->config['start_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }

                if($this->config['end_date'] == '' ){
                    $this->config['end_date'] = \carbon\Carbon::now()->format('Y-m-d H:i:s');
                }
                $baseQuery = $baseQuery->whereBetween('date',[$this->config['start_date'], $this->config['end_date']]);
                break;
        }

        $timeList = array(
            'Pasien Baru' => 0,
            'Pasien Lama' => 0
        );
        foreach ($baseQuery->get() as $value) {
            $key = "Pasien Lama";
            if($value->patient->old_patient == "n") {
                $key = "Pasien Baru";
            }

            $timeList[$key] += ($timeList[$key] + 1);

        }

        return $timeList;
    }

    public function chart() {
        $data = new Collection($this->_init());
        $charts = new PatientPeakTimeChart;
        $warna = rcolor()->listColor(count($data));
        $charts->labels($data->keys());
        $charts->minimalist(true);
        $charts->dataset('Jumlah Pasien', 'pie', $data->values())->color($warna);
        $charts->options([
            'plotOptions' => [
                'pie'=> [
                    'allowPointSelect'=> true,
                    'cursor'=> 'pointer',
                    'dataLabels'=> [
                        'enabled'=> true,
                        'format'=> '<b>Jam.{point.name}</b>= {point.percentage:.1f}',
                        'connectorColor'=> 'silver'
                    ],
                    'showInLegend'=> false
                ]
            ],
        ]);

        return $charts;
    }


    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $statisticPatient = $this->chart();

        return view('widgets.statistic_patient', [
            'config' => $this->config,
            'chartStatisticPatient' => $statisticPatient
        ]);
    }
}
