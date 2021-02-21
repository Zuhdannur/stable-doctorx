<?php

namespace App\Modules\Product\Models\Traits\Attribute;

trait ProductAttribute
{
    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.product.edit', $this).'" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.edit').'"><i class="fa fa-edit"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getOpnameButtonAttribute()
    {
        return '<a href="'.route('admin.product.opname', $this).'" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Stock Opname"><i class="fa fa-clipboard"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        return '<a href="'.route('admin.product.destroy', $this).'"
			 data-method="delete"
			 data-trans-button-cancel="'.__('buttons.general.cancel').'"
			 data-trans-button-confirm="'.__('buttons.general.crud.delete').'"
			 data-trans-title="'.__('strings.backend.general.are_you_sure').'"
			 class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.delete').'"><i class="fa fa-trash"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '<div class="btn-group btn-group-sm" role="group" aria-label="'.__('labels.backend.access.users.user_actions').'">
                '.$this->opname_button.'
			    '.$this->edit_button.'
			    '.$this->delete_button.'
			</div>';
    }

    /**
     * @return string
     */
    public function getEditPointButtonAttribute()
    {
        return '<a href="'.route('admin.crm.point.obat.edit', $this).'" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="Edit Point"><i class="fa fa-edit"></i></a> &nbsp;';
    }
}
