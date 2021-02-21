<?php

namespace App\Modules\Patient\Models\Traits\Attribute;

trait TreatmentAttribute
{
    /**
     * @return string
     */
    public function getViewButtonAttribute()
    {
        return '<a href="'.route('admin.patient.treatment.show', $this).'" class="btn btn-sm btn-'.$this->status->class_style.' mr-5 mb-5" data-toggle="tooltip" data-placement="top" title="Detail"><i class="fa fa-file-text-o"></i></a>';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        return '<div class="btn-group btn-group-sm" role="group" aria-label="'.__('labels.backend.access.users.user_actions').'">
			  '.$this->view_button.'
			</div>';
    }
}
