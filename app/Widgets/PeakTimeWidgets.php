<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\LogFrontEnd;
use App\Charts\PeakTime;
use Illuminate\Support\Collection;
use App\Helpers\General\RandomColor;

class PeakTimeWidgets extends AbstractWidget
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
        'type' => '4',  //1 => by This Day, 2 => by This Month, 3 => by This Years, 4 => all per hours
        'filter' => '', //1 -10
        'start_date' => '',
        'end_date' => ''
    ];

    protected $label;

    protected function _byThisYear()
    {
        $end_day = \Carbon\Carbon::now()->endOfYear();

        $data[] = array();
        for ($i=0; $i <= $end_day->format('m'); $i++) { 
            $date = new \Carbon\Carbon;
            $date = $date->now()->startOfYear()->addMonth($i);
            $data[$date->locale('id')->monthName] = LogFrontEnd::whereMonth('created_at', $date )->count();
        }  

        return $data;
    }
    protected function _byThisMonth()
    {
        $end_day = \Carbon\Carbon::now();

        $data[] = array();
        for ($i=0; $i <= $end_day->format('d'); $i++) { 
            $date = new \Carbon\Carbon;
            $date = $date->now()->startOfMonth()->addDay($i);
            $data[$date->format('d')] = LogFrontEnd::whereDate('created_at', $date)->count();
        }  

        return $data;
    }

    protected function _byThisDay()
    {
        $end_hours = \Carbon\Carbon::now()->endOfDay();

        $data[] = array();
        for ($i=0; $i <= $end_hours->format('H'); $i++) { 
            $start_time = new \Carbon\Carbon;
            $end_time = new \Carbon\Carbon;
    
            $start_time = $start_time->now()->startOfDay()->addHour($i);
            $end_time = $end_time->now()->startOfDay()->addHour($i + 1);
            $data[$start_time->format('H')] = LogFrontEnd::where('created_at', '>', $start_time)->where('created_at', '<',$end_time)->count();
        }

        return $data;
    }

    public function _traffictPerHours()
    {
        $end_hours = \Carbon\Carbon::now()->endOfDay();

        $data[] = array();
        for ($i=0; $i <= $end_hours->format('H'); $i++) { 
            $start_time = new \Carbon\Carbon;
            $end_time = new \Carbon\Carbon;
    
            $start_time = $start_time->now()->startOfDay()->addHour($i);
            $end_time = $end_time->now()->startOfDay()->addHour($i + 1);
            $data[$start_time->format('H')] = LogFrontEnd::whereTime('created_at', '>', $start_time)->whereTime('created_at', '<',$end_time)->count();
        }

        return $data;   
    }
    
    protected function _traffictPerHoursWithFilter()
    {
        
        switch ($this->config['filter']) {
            case '1': //all
                $data = LogFrontEnd::get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });
                break;
            case '2': //today
                $data = LogFrontEnd::whereDate('created_at', \Carbon\Carbon::now() )->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '3': //Yesterday
                $data = LogFrontEnd::whereDate('created_at', \Carbon\Carbon::now()->subDay(1) )->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '4': //This Week
                $data = LogFrontEnd::whereBetween('created_at', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()] )->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '5': //Last Week
                $now = \Carbon\Carbon::now(); 
                $data = LogFrontEnd::whereBetween('created_at', [$now->subDay($now->dayOfWeek  + 1), $now->endOfWeek()] )->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '6': //This Month
                $data = LogFrontEnd::whereMonth('created_at', \Carbon\Carbon::now())->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });
              
                break;
            case '7': //Last Month
                $data = LogFrontEnd::whereMonth('created_at', \Carbon\Carbon::now()->subMonth(1))->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });

                break;
            case '8': //This Year
                $data = LogFrontEnd::whereYear('created_at', \Carbon\Carbon::now())->get()
                ->groupBy(function($data){
                    return \Carbon\Carbon::parse($data->date)->format('H');
                })
                ->map(function($item){
                    return count($item);
                });
                break;
            case '9': //Last Year
                $data = LogFrontEnd::whereYear('created_at', \Carbon\Carbon::now()->subYear(1))->get()
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
        
                $data = LogFrontEnd::whereBetween('created_at',[$this->config['start_date'], $this->config['end_date']])->get()
                    ->groupBy(function($data){
                        return \Carbon\Carbon::parse($data->created_at)->format('H');
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
    
    public function chartPeakTime(){
        
        $data = '';

        switch ($this->config['type']) {
            case '1':
                $data = new Collection($this->_byThisDay());
                $this->label = \Carbon\Carbon::now()->locale('id')->dayName;
                $this->label .= ', '.\Carbon\Carbon::now()->format('d-m-Y');
                break;
            case '2':
                $data = new Collection($this->_byThisMonth());
                $this->label = \Carbon\Carbon::now()->locale('id')->monthName;
                $this->label .= ', '.\Carbon\Carbon::now()->format('Y');
                break;
            case '3':
                $data = new Collection($this->_byThisYear());
                $this->label .= 'Tahun ini';
                break;
            case '4':
                $data = new Collection($this->_traffictPerHours());
                $this->label = 'Per Jam';
                break;
            case '5':
                $data = new Collection($this->_traffictPerHoursWithFilter());
                $this->label = 'Per Jam';
                break;
            default:
                $data = new Collection($this->_byThisDay());
                $this->label = \Carbon\Carbon::now()->locale('id')->dayName;
                $this->label .= ', '.\Carbon\Carbon::now()->format('d-m-Y');
                break;
        }

        $charts = new PeakTime;

        $total = 0;
        foreach($data as $val){
            $total = $total + $val;
        }

        $charts->labels($data->keys());
        $charts->minimalist(false);
        $charts->displayLegend(true);
        $charts->displayAxes(true);
        $charts->dataset('Total Request = '.$total,'line', $data->values())
        ->backgroundColor('rgb(99, 191, 212, 80)')
        ->options([
            'cursor'=> 'pointer',
            "pointBackgroundColor" => '#0098c2',
        ]);
       
        return $charts;
    }

    
    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $chartPeakTime = $this->chartPeakTime();
        $label = $this->label;

        return view('backend.chart.peak-time', compact(['chartPeakTime', 'label']));
    }
    
}
