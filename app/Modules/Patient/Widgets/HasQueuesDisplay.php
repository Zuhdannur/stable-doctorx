<?php

namespace App\Modules\Patient\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\Treatment;
use App\Modules\Patient\Models\AdmissionType;

class HasQueuesDisplay extends AbstractWidget
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
                    </div>';
    }

    /**
     * The number of seconds before each reload.
     *
     * @var int|float
     */
    public $reloadTimeout = 5;

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
        
    ];

    public function appointment()
    {
        // $data = Appointment::whereIn('status_id', [1, 2])->whereDate('date', \Carbon\Carbon::today())->orderBy('date', 'asc')->take(5)->get();

        $data = Appointment::leftJoin('rooms', 'rooms.id', '=', 'appointments.room_id')
        ->leftJoin('room_groups', 'room_groups.id', '=', 'rooms.room_group_id')
        ->leftJoin('floors', 'floors.id', '=', 'room_groups.floor_id')
        ->whereIn('status_id', [1, 2])
        ->whereDate('date', \Carbon\Carbon::today())
        ->select('appointments.*', 'rooms.name as room_name', 'floors.name as floor_name')
        ->orderBy('date', 'asc')
        ->take(5)->get();
        // die(json_encode($data->groupBy('floor_name')));
        return $data;
    }

    public function treatment()
    {
        // $data = Treatment::whereIn('status_id', [1, 2])->whereDate('date', \Carbon\Carbon::today())->orderBy('date', 'asc')->take(5)->get();
        $data = Treatment::leftJoin('rooms', 'rooms.id', '=', 'treatments.room_id')
        ->leftJoin('room_groups', 'room_groups.id', '=', 'rooms.room_group_id')
        ->leftJoin('floors', 'floors.id', '=', 'room_groups.floor_id')
        ->whereIn('status_id', [1, 2])
        ->whereDate('date', \Carbon\Carbon::today())
        ->select('treatments.*', 'rooms.name as room_name', 'floors.name as floor_name')
        ->orderBy('date', 'asc')
        ->take(5)->get();
        
        return $data;
    }

    public function medicine()
    {
        $data = AdmissionType::get();
        return $data;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        // print_r($this->config);
        return view('patient::widgets.admission.has-queues-display', [
            'config' => $this->config,
            'appointment' => $this->appointment(),
            'treatment' => $this->treatment(),
            'medicine' => $this->medicine(),
        ]);
    }
}
