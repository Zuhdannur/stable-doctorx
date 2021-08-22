<?php

namespace App\Modules\Crm\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Modules\Crm\Models\CrmMembership;
use App\Modules\Crm\Models\CrmRadeemPoint;
use App\Modules\Crm\Models\CrmMsMembership;
use App\Modules\Crm\Charts\MembershipCategories;

use Illuminate\Support\Facades\DB;
use App\Helpers\General\RandomColor;

class MembershipWidgets extends AbstractWidget
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
        'grade' => 'semua'
    ];

    public function membership()
    {
//        $data = CrmMembership::with('ms_membership')->get()
//            ->groupBy('ms_membership.name')
//            ->map(function ($item) {
//                // Return the number of persons with that age
//                return count($item);
//            });

        $data = [0,0,0,0];
        $labels = ['','','',''];

        $query = CrmMembership::where('id_klinik',auth()->user()->id_klinik);
    
        if($this->config['grade'] != "semua") {
            $query = $query->where('id_grade',$this->config['grade']);
        }

        foreach ($query->get() as $row) {

            $totalAmmount = 0;
            foreach ($row->invoice as $item) {
                $totalAmmount += $item->total_ammount;
            }

            if($totalAmmount < 1000000) {
                $data[0] += 1;
                $labels[0] = '< Rp 1.000.000';
            }

            if($totalAmmount >= 1000000 && $totalAmmount < 3000000) {
                $data[1] += 1;
                $labels[1] = 'Rp 1.000.000 - Rp 3.000.000';
            }

            if($totalAmmount >= 3000000 && $totalAmmount < 5000000) {
                $data[2] += 1;
                $labels[2] = 'Rp 3.000.000 - Rp 5.000.000';
            }

            if($totalAmmount >= 5000000) {
                $data[3] += 1;
                $labels[3] = '> Rp 5.000.000';
            }


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
        $chart = new MembershipCategories;
        $chart->labels($labels);
        // $chart->title('siapp');
        // $chart->doughnut(50);
        $chart->minimalist(false);
        $chart->displayLegend(true);
        $chart->displayAxes(true);
        $chart->dataset('Total', 'pie', $data)->color($warna);
        $chart->options([
            'plotOptions' => [
                'pie'=> [
                    'allowPointSelect'=> true,
                    'cursor'=> 'pointer',
                    'dataLabels'=> [
                        'enabled'=> true,
//                        'format'=> '<b>{point.name}</b>: {point.percentage:.1f} %',
                        'format'=> '<b>{point.name}</b>',
                        'connectorColor'=> 'silver'
                    ],
                    'showInLegend'=> false
                ]
            ],
        ]);

        return $chart;
    }

    public function radeem_point()
    {
        $month = \Carbon\Carbon::now()->addMonth(0)->format('m');

        $data = CrmRadeemPoint::with('membershipCategory')->whereMonth('created_at', $month)->get();

        $collection = array();
        foreach($data as $key => $val){
            foreach($val->membershipCategory as $key_2 => $val_2){
                $total_point = $val->point * $val->ammount;
                $collection[] = array(
                    'name' => $val_2->name,
                    'membership_id' => $val->membership_id,
                    'item_code' => $val->item_code,
                    'point' => $val->point,
                    'ammount' => $val->ammount,
                    'total_point' => $total_point,
                    'nominal' => $val->nominal,
                    'total_nominal' => $total_point * $val->nominal,
                );
            }
        }

        $collection = collect($collection);

        $data = $collection->groupBy('name')
                        ->map(function ($item) {
                        return $item->sum('total_point');
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
        // $color
        $warna = rcolor()->listColor(count($data));
        $chart = new MembershipCategories;
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

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $membership = $this->membership();
        $radeem_point = $this->radeem_point();

        return view('crm::widgets.membership', compact('membership', 'radeem_point'));
    }

}
