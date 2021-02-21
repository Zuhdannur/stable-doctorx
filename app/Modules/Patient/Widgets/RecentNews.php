<?php

namespace App\Modules\Patient\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Models\Appointment;
use App\Modules\Patient\Models\Treatment;
use App\Modules\Patient\Models\Patient;
use function foo\func;

class RecentNews extends AbstractWidget
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
        $data = new \stdClass();
        $data->total_appointment = Appointment::whereHas('patient',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->count();
        $data->total_appointment_today = Appointment::whereHas('patient',function ($query) {
        return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
    })->whereDate('date', \Carbon\Carbon::today())->count();

        return $data;
    }

    public function treatment()
    {
        $data = new \stdClass();
        $data->total_treatment = Treatment::whereHas('patient',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->count();
        $data->total_treatment_today = Treatment::whereHas('patient',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->whereDate('date', \Carbon\Carbon::today())->count();

        return $data;
    }

    public function patient()
    {
        $data = new \stdClass();
        // $data->total_patient = Patient::count();
        // $data->total_patient_today = Patient::whereDate('created_at', \Carbon\Carbon::today())->count();
        $today = \Carbon\Carbon::today();
        $treatmentPasienLama = Treatment::whereHas('patient',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->whereDate('date', $today)
                        ->whereHas('patient', function($q) use($today)  {
                            return $q->whereDate('created_at', '!=', $today);
                        })
                        ->count();

        $appointmentPasienLama = Appointment::whereHas('patient',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->whereDate('date', $today)
                        ->whereHas('patient', function($q) use($today) {
                            return $q->whereDate('created_at', '!=', $today);
                        })
                        ->count();

        $treatmentPasienBaru = Treatment::
        whereHas('patient',function ($query) {
            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
        })->whereDate('date', $today)
                        ->whereHas('patient', function($q) use($today) {
                            return $q->whereDate('created_at', $today);
                        })
                        ->count();

        $appointmentPasienBaru = Appointment::whereHas('patient',function ($query) {
                            return $query->where('id_klinik',Auth()->user()->klinik->id_klinik);
                    })->whereDate('date', $today)
                        ->whereHas('patient', function($q) use($today) {
                            return $q->whereDate('created_at', $today);
                        })
                        ->count();

        $data->pasienLama = $treatmentPasienLama + $appointmentPasienLama;
        $data->pasienBaru = $treatmentPasienBaru + $appointmentPasienBaru;

        return $data;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('patient::widgets.recent_news', [
            'config' => $this->config,
            'appointment' => $this->appointment(),
            'treatment' => $this->treatment(),
            'patient' => $this->patient()
        ]);
    }
}
