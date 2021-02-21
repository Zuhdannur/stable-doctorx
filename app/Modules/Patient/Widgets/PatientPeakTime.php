<?php

namespace App\Modules\Patient\Widgets;

use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Collection;
use App\Modules\Patient\Charts\PatientPeakTimeChart;
use App\Modules\Patient\Models\Appointment;

class PatientPeakTime extends AbstractWidget
{
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
        'filter' => '1', // 1-10
        'start_date' => '',
        'end_date' => ''
    ];

    protected $label;

    protected function _peakWithFilter(){

        switch ($this->config['filter']) {
            case '1': //all
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });
                break;
            case '2': //today
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereDate('date', \Carbon\Carbon::now() )->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '3': //Yesterday
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereDate('date', \Carbon\Carbon::now()->subDay(1) )->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '4': //This Week
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereBetween('date', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()] )->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now();
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereBetween('date', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()] )->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '6': //This Month
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereMonth('date', \Carbon\Carbon::now())->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '7': //Last Month
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereMonth('date', \Carbon\Carbon::now()->subMonth(1))->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '8': //This Year
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereYear('date', \Carbon\Carbon::now())->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });
                break;
            case '9': //Last Year
                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereYear('date', \Carbon\Carbon::now()->subYear(1))->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
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

                $data = Appointment::whereHas('patient',function ($query){
                    return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                })->whereBetween('date',[$this->config['start_date'], $this->config['end_date']])->get()
                    ->groupBy(function($data){
                        return \Carbon\Carbon::parse($data->date)->format('H');
                    })
                    ->map(function($item){
                        return count($item);
                });
                break;
        }

        $timeList = array(
            '00' => '0',
            '01' => '0',
            '02' => '0',
            '03' => '0',
            '04' => '0',
            '05' => '0',
            '06' => '0',
            '07' => '0',
            '08' => '0',
            '09' => '0',
            '10' => '0',
            '11' => '0',
            '12' => '0',
            '13' => '0',
            '14' => '0',
            '15' => '0',
            '16' => '0',
            '17' => '0',
            '18' => '0',
            '19' => '0',
            '20' => '0',
            '21' => '0',
            '22' => '0',
            '23' => '0',
        );

        foreach ($data as $key => $value) {
            $timeList[$key] = $value;
        }

        return $timeList;
    }

    public function chart()
    {
        $data = new Collection($this->_peakWithFilter());
        $this->label = 'Per Jam';
        $total = 0;
        foreach($data as $val){
            $total = $total + $val;
        }
        $charts = new PatientPeakTimeChart;
        $warna = rcolor()->listColor(count($data));

        $charts->labels($data->keys());
        // $charts->minimalist(true);
        // $charts->displayLegend(true);
        // $charts->displayAxes(true);
        // $charts->dataset('Jumlah Layanan = '.$total,'pie', $data->values())
        // ->backgroundColor('rgb(84, 184, 110)')
        // ->options([
            //     'cursor'=> 'pointer',
            //     "pointBackgroundColor" => '#55ab6c',
            // ]);
        $charts->labels($data->keys());
        $charts->minimalist(true);
        $charts->dataset('Jumlah Layanan', 'pie', $data->values())->color($warna);
        $charts->options([
            'plotOptions' => [
                'pie'=> [
                    'allowPointSelect'=> true,
                    'cursor'=> 'pointer',
                    'dataLabels'=> [
                        'enabled'=> true,
                        'format'=> '<b>Jam.{point.name}</b>= {point.percentage:.1f} %',
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
        $chartPatientPeakTime = $this->chart();
        $label = $this->label;

        return view('patient::widgets.patient-peak-time', compact(['chartPatientPeakTime', 'label']));
    }
}
