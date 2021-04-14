<?php

namespace App\Modules\Patient\Models\Traits\Attribute;

trait AppointmentAttribute
{
    /**
     * @return string
     */
    public function getViewButtonAttribute()
    {
        return '<a href="'.route('admin.patient.appointment.show', $this).'" class="btn btn-sm btn-secondary mr-5 mb-5" data-toggle="tooltip" data-placement="top" title="Lihat Data"><i class="fa fa-file-text-o"></i></a>';
    }

    /**
     * @return string
     */
    public function getViewPrescriptionButtonAttribute()
    {
        $appointment = $this;
        $presId = null;
        if(!empty($appointment->prescription)){
            $presId = $this->prescription->id;
        }

        return '<a href="'.route('admin.patient.prescription.show', $presId).'" class="btn btn-sm btn-primary mr-5 mb-5" data-toggle="tooltip" data-placement="top" title="Hasil Rekam Medis"><i class="fa fa-drivers-license-o"></i></a>';
    }

    /**
     * @return string
     */
    public function getEditPrescriptionButtonAttribute()
    {
        $appointment = $this;
        $presId = null;
        if(!empty($appointment->prescription)){
            $presId = $this->prescription->id;
        }

        return '<a href="'.route('admin.patient.prescription.edit', $presId).'" class="btn btn-sm btn-success mr-5 mb-5" data-toggle="tooltip" data-placement="top" title="Edit Rekam Medis"><i class="fa fa-drivers-license-o"></i></a>';
    }

    /**
     * @return string
     */
    public function getCreateButtonAttribute()
    {
        return '<a href="'.route('admin.patient.prescription.create', $this->appointment_no).'" class="btn btn-sm btn-success mr-5 mb-5" data-toggle="tooltip" data-placement="top" title="Input Resep/Hasil Diagnosa"><i class="fa fa-medkit"></i></a>';
    }

    /**
     * @return string
     */
    public function getEditButtonAttribute()
    {
        return '<a href="'.route('admin.patient.appointment.getFormEdit', $this).'"  class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="'.__('buttons.general.crud.edit').'"><i class="fa fa-edit"></i></a> &nbsp;';
    }

    /**
     * @return string
     */
    public function getViewConsentAttribute()
    {
        return '<button style="width: 50%" class="btn btn-sm btn-warning mr-5 mb-5 btnConsent" value="'.@$this->prescription->inform_concern.'" data-toggle="tooltip" data-placement="top" title="Lihat Consent"><i class="fa fa-check-circle-o"></i></a>';
    }

    /**
     * @return string
     */
    public function getViewMappingAttribute()
    {
        return '<div><input type="text" value="'.@$this->prescription->mapping->image.'" hidden>
        <button class="btn btn-sm btn-info mr-5 mb-5 btnMapping" data-toggle="tooltip" data-placement="top" title="Lihat Record Mapping"><i class="fa fa-user"></i></button></div>';
    }

    /**
     * @return string
     */
    public function getActionButtonsAttribute()
    {
        $appointment = $this;
        $btn = null;
        $btnEdit = null;
        $btnV = null;
        $btnVPre = null;
        $btnViewConsent = null;
        $btnMapping = null;

        switch ($appointment->status_id) {
            case '1': //waiting
                $btn = $this->create_button;
                $btnEdit = $this->edit_button;
                break;
            case '4' : //completed
                if($appointment->billing){
                    if($appointment->billing->status != 0){
                        $btnV = $this->view_prescription_button;
                    }else{
                        if(!empty($this->prescription->inform_concern)) {
                            $btnViewConsent = $this->view_consent;
                        }

                        if(!empty($this->prescription->mapping)) {
                            $btnMapping = $this->view_mapping;
                        }
                        $btnVPre = $this->edit_prescription_button;
                    }
                }else{
                    $btnV = $this->view_prescription_button;
                }
                break;

        }

        return '<div class="btn-group btn-group-sm" role="group" aria-label="'.__('labels.backend.access.users.user_actions').'">
              '.$btnEdit.'
			  '.$this->view_button.'
              '.$btnVPre.'
              '.$btnViewConsent.'
              '.$btnMapping.'
              '.$btnV.'
              '.$btn.'
			</div>';
    }
}
