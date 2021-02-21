<?php

namespace App\Modules\Crm\Models\Traits\Attribute;

/**
 * Membership Attribuutes
 */
trait MembershipAttributes
{
    /**
     * @return string
     */

    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.crm.settings.membership.show', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Ubah Data Membership"><i class="fa fa-edit"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getDeleteButtonAttribute()
    {
        return '<a href="'.route('admin.crm.settings.membership.destroy', $this).'"
			 data-method="delete"
			 data-trans-button-cancel="'.__('buttons.general.cancel').'"
			 data-trans-button-confirm="'.__('buttons.general.crud.delete').'"
			 data-trans-title="'.__('strings.backend.general.are_you_sure').'"
			 class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.delete').'"><i class="fa fa-trash"></i></a> &nbsp;';
    }

    /**
     * @return string
     */

    public function getEditMembershipButtonAttribute()
    {
        return '<a href="'.route('admin.crm.membership.edit', $this).'" class="btn btn-sm btn-success" data-toggle="tooltip" data-placement="top" title="Ubah Data Membership"><i class="fa fa-edit"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getDeleteMembershipButtonAttribute()
    {
        return '<a href="'.route('admin.crm.membership.destroy', $this).'"
			 data-method="delete"
			 data-trans-button-cancel="'.__('buttons.general.cancel').'"
			 data-trans-button-confirm="'.__('buttons.general.crud.delete').'"
			 data-trans-title="'.__('strings.backend.general.are_you_sure').'"
			 class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.delete').'"><i class="fa fa-trash"></i></a> &nbsp;';
    }

     /**
     * @return string
     */

    public function getShowMembershipButtonAttribute()
    {
        return '<a href="'.route('admin.crm.membership.show', $this).'" class="btn btn-sm btn-info" data-toggle="tooltip" data-placement="top" title="Detail Data Membership"><i class="fa fa-eye"></i></a> &nbsp;';
    }

}
