<?php

namespace App\Modules\Patient\Models\Traits\Attribute;

trait PatientAttribute
{
    /**
     * @return string
     */
    public function getRevisitButtonAttribute()
    {
        return '<button class="btn btn-sm btn-outline-info mb-10" data-toggle="tooltip" data-placement="top" title="Revisit"><i class="fa fa-exchange"></i></button> &nbsp;';
    }

    /**
     * @return string
     */
    public function getShowButtonAttribute()
    {
        return '<a href="'.route('admin.patient.show', $this).'" class="btn btn-sm btn-outline-success mb-10" data-toggle="tooltip" data-placement="top" title="Lihat Pasien"><i class="fa fa-reorder"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '<div class="btn-group btn-group-sm" role="group" aria-label="'.__('labels.backend.access.users.user_actions').'">
			  '.$this->show_button.'
			</div>';
    }

    /**
     * @return string
     */
    public function getSendWaButtonsAttribute()
    {
        return '<button class="btn btn-sm btn-success" onClick=sendWa("'.$this->id.'") data-toggle="tooltip" data-placement="top" title="Kirim Ucapan Ultah Via WA"><i class="fa fa-whatsapp"></i></button> &nbsp;';
    }

    /**
     * @return string
     */
    public function getEditWaButtonsAttribute()
    {
        return '<a href="'.route('admin.crm.birthday.editNoWA', $this).'" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit/Tambah"><i class="fa fa-edit"></i></a> &nbsp;';
    }
}
