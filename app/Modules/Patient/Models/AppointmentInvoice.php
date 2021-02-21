<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentInvoice extends Model
{
	public $timestamps = false;

    protected $fillable = [
    	'appointment_id', 
    	'invoice_id'
    ];

    public function appointment()
    {
        return $this->hasOne('App\Modules\Patient\Models\Appointment', 'id', 'appointment_id');
    }
}
