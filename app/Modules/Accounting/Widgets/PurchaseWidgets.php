<?php

namespace App\Modules\Accounting\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Modules\Accounting\Models\FinancePurchase;

class PurchaseWidgets extends AbstractWidget
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

    ];

    public function unpaid()
    {
        $now = \Carbon\Carbon::today();

        $unpaid = FinancePurchase::where('status', '0') //0 unpaid
        ->sum('remain_payment');

        return $unpaid;
    }

    public function due_date()
    {
        $now = \Carbon\Carbon::today();

        $unpaid = FinancePurchase::where('status', '0') //0 unpaid
        ->where('due_date', '>=', $now)
        ->sum('remain_payment');

        return $unpaid;
    }

    public function paid_last_30_days()
    {
        return FinancePurchase::with('financeTrx')
        ->whereHas('financeTrx', function($q){
            return $q->where('trx_date','>=', \Carbon\Carbon::now()->subdays(30));
        })
        ->where('status', '1') // 1 = paid
        ->sum('total');
    }

     /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $unpaid = $this->unpaid();
        $due_date = $this->due_date();
        $paid_last_30_days = $this->paid_last_30_days();

        return view('accounting::widgets.purchasing', compact('unpaid','paid_last_30_days','due_date'));
    }
}