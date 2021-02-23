<?php

namespace App\Modules\Incentive\Models\Traits\Attribute;

trait IncentiveAttribute
{
    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.incentive.edit', $this).'" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.edit').'"><i class="fa fa-edit"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getDetailButtonAttribute()
    {
        return '<button class="btn btn-success details-control" data-toggle="tooltip" data-placement="top" title="Daftar Produk"><i class="fa fa-list"></i></button> &nbsp;';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        return '<a href="'.route('admin.incentive.destroy', $this).'"
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
              '.$this->edit_button.'
              '.$this->detail_button.'
              '.$this->delete_button.'
			</div>';
    }
}
