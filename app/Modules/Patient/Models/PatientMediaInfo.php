<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;

class PatientMediaInfo extends Model
{
    public $timestamps = false;

    protected $table = 'patient_media_info';

    protected $fillable = [
        'patient_id', 
        'media_id'
    ];

    public function media()
    {
        return $this->hasOne('App\Modules\Attribute\Models\AttributeInfoMedia', 'media_id', 'id');
    }

    public function medianame()
    {
        return $this->hasOne('App\Modules\Attribute\Models\AttributeInfoMedia', 'id', 'media_id');
    }

    public function patient()
    {
        return $this->hasOne('App\Modules\Patient\Models\Patient', 'id', 'patient_id');
    }
}
