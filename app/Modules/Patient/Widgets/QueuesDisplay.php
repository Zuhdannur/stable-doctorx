<?php

namespace App\Modules\Patient\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Models\PatientAdmission;
use App\Modules\Patient\Models\AdmissionType;

class QueuesDisplay extends AbstractWidget
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
     * The number of seconds before each reload.
     *
     * @var int|float
     */
    public $reloadTimeout = 3;

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
        $data = PatientAdmission::orderBy('created_at', 'desc')->take(5)->get();

        return $data;
    }

    public function today()
    {
        $data = PatientAdmission::where('status_id', config('admission.admission_waiting'))->whereDate('created_at', \Carbon\Carbon::today())->orderBy('created_at', 'asc')->orderBy('status_id', 'asc')->get();
        return $data;
    }

    public function types()
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
        return view('patient::widgets.admission.queues-display', [
            'config' => $this->config,
            'latest' => $this->latest(),
            'today' => $this->today(),
            'types' => $this->types(),
        ]);
    }
}
