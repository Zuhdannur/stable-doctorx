<?php

namespace App\Modules\Patient\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\AdmissionType;

class RecentAppointments extends AbstractWidget
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

    public function latest()
    {
        $data = Appointment::where('id_klinik', auth()->user()->klinik->id_klinik)->orderBy('date', 'desc')->take(5)->get();

        return $data;
    }

    public function today()
    {
        $data = Appointment::where('id_klinik', auth()->user()->klinik->id_klinik)->whereDate('date', \Carbon\Carbon::today())->orderBy('date', 'asc')->take(5)->get();
        return $data;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        // print_r($this->config);
        return view('patient::widgets.recent_appointments', [
            'config' => $this->config,
            'latest' => $this->latest(),
            'today' => $this->today(),
        ]);
    }
}
