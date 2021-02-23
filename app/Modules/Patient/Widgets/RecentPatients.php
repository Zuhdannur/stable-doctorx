<?php

namespace App\Modules\Patient\Widgets;

use App\Helpers\Auth\Auth;
use Arrilot\Widgets\AbstractWidget;

use App\Modules\Patient\Models\Patient;

class RecentPatients extends AbstractWidget
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

    public function patient()
    {
        $data = Patient::where('id_klinik',Auth()->user()->klinik->id_klinik)->orderBy('created_at', 'desc')->take(5)->get();

        return $data;
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('patient::widgets.recent_patients', [
            'config' => $this->config,
            'patients' => $this->patient()
        ]);
    }
}
