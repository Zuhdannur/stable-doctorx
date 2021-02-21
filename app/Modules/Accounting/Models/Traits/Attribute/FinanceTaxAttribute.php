<?php

namespace App\Modules\Accounting\Models\Traits\Attribute;

/**
 * trait for finance tax
 */
trait FinanceTaxAttribute
{
    /**
     * @return string
     */

    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.settings.tax.edit', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Edit Data Pajak"><i class="fa fa-edit"></i></a> &nbsp;';
    }
}