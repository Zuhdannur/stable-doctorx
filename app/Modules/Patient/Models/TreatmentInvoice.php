<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentInvoice extends Model
{
	public $timestamps = false;

    protected $fillable = [
    	'treatment_id', 
    	'invoice_id'
    ];

    public function treatment()
    {
        return $this->hasOne('App\Modules\Patient\Models\Treatment', 'id', 'treatment_id');
    }
}
