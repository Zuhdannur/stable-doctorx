<?php

namespace App\Modules\Crm\Models\Traits\Attribute;

/**
 * trait for Crm Marketing
 */
trait MarketingAttributes
{
    /**
     * @return string
     */

    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.crm.marketing.edit', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.edit').'"><i class="fa fa-edit"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        return '<a href="'.route('admin.crm.marketing.destroy', $this).'"
			 data-method="delete"
			 data-trans-button-cancel="'.__('buttons.general.cancel').'"
			 data-trans-button-confirm="'.__('buttons.general.crud.delete').'"
			 data-trans-title="'.__('strings.backend.general.are_you_sure').'"
			 class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.delete').'"><i class="fa fa-trash"></i></a> &nbsp;';
    }
}
