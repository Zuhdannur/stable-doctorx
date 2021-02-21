<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAdmission extends Model
{
    public $timestamps = false;

    protected $dates = ['created_at', 'updated_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }

    public static function generateQueue($typeId)
    {
        $findType = \App\Modules\Patient\Models\AdmissionType::findOrFail($typeId);
        $separator = '-';
        $code = strtoupper(substr($findType->name, 0, 1)).$separator;

        $count = self::whereDate('created_at', \Carbon\Carbon::today())->where('admission_type_id', $typeId)->count();

        $newQueueId = $code . str_pad($count + 1, 3, 0, STR_PAD_LEFT);

        return $newQueueId;
    }

    protected $fillable = [
        'patient_id', 
        'admission_no', 
        'admission_type_id',
        'status_id',
        'is_online',
        'reference_id',
        'notes'
    ];

    public function patient()
    {
        return $this->hasOne('App\Modules\Patient\Models\Patient', 'id', 'patient_id');
    }

    public function typename()
    {
        return $this->hasOne('App\Modules\Patient\Models\AdmissionType', 'id', 'appointment_type_id');
    }

    public function status()
    {
        return $this->hasOne('App\Modules\Patient\Models\AppointmentStatus', 'id', 'status_id');
    }
}
