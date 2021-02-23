<?php

namespace App\Modules\Accounting\Models\Traits\Attribute;

/**
 * trait for category account
 */

trait FinanceAccountCategoryAttribute
{
    /**
     * @return string
     */

    public function getEditMsButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.settings.ms_categories.edit', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Edit Master Categories"><i class="fa fa-edit"></i></a> &nbsp;';
    }

    /**
     * @return string
     */

    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.accounting.settings.categories.edit', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Edit Categories"><i class="fa fa-edit"></i></a> &nbsp;';
    }
}
