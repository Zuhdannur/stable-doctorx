<?php

namespace App\Modules\Accounting\Models\Traits\Attribute;

trait FinancePurchaseAttribute{
     /**
     * @return string
     */

    public function getShowButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.purchase.show', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-list"></i></a> &nbsp;';
    }

    /**
     * @return string
     */

    public function getPayButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.purchase.pay', $this).'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Lunasi"><i class="fa fa-credit-card"></i></a> &nbsp;';
    }
}