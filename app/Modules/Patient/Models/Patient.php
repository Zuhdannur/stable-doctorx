<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Patient\Models\Traits\Attribute\PatientAttribute;

class Patient extends Model
{
    use PatientAttribute;

    public $timestamps = false;

    protected $dates = ['dob', 'created_at', 'updated_at', 'deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();

            if(!$model->id)
			{
				$data = self::orderBy('created_at','DESC')->first();
				$lastId = $data ? $data->id : 0;
				$year = date('ym');
				$n = str_pad($lastId + 1, setting()->get('digit_sequence'), 0, STR_PAD_LEFT);
                $patientCode = setting()->get('patient_code');
				$sequence = $patientCode.$n;
				$model->patient_unique_id = $sequence;
			}
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }

    public function setDobAttribute($value)
	{
	    $this->attributes['dob'] = \Carbon\Carbon::createFromFormat(setting()->get('date_format'), $value)->format('Y-m-d');
	}

    public function getDobFormattedAttribute()
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d', $this->attributes['dob'])->format(setting()->get('date_format'));
    }

    public function getDobStringAttribute()
    {
        return timezone()->convertToLocal($this->dob, 'date');
    }

    public function getOldPatientStringAttribute()
    {
        return $this->attributes['old_patient'] == 'y' ? 'Pasien Lama' : 'Pasien Baru';
    }

	public function getAgeAttribute()
	{
	    return \Carbon\Carbon::parse($this->attributes['dob'])->age;
	}

    public function getAgeRangeAttribute()
    {
        if($this->age < 20){
            return '< 20';
        }else if($this->age > 20 && $this->age < 30){
            return '20 - 30';
        }else if($this->age > 30 && $this->age < 40){
            return '30 - 40';
        }else{
            return '> 40';
        }

    }
    public function flag()
    {
        return $this->hasOne('App\Modules\Patient\Models\PatientFlag', 'id', 'patient_flag_id');
    }

    public function religion()
    {
        return $this->hasOne('App\Modules\Attribute\Models\AttributeReligion', 'id', 'religion_id');
    }

    public function info()
    {
        return $this->hasOne('App\Modules\Attribute\Models\AttributeInfoSource', 'id', 'info_id');
    }

    public function work()
    {
        return $this->hasOne('App\Modules\Attribute\Models\AttributeWork', 'id', 'work_id');
    }

    public function blood()
    {
        return $this->hasOne('App\Modules\Attribute\Models\AttributeBloodBank', 'id', 'blood_id');
    }

    public function city()
    {
        return $this->hasOne('App\Modules\Indonesia\Models\City', 'id', 'city_id');
    }

    public function district()
    {
        return $this->hasOne('App\Modules\Indonesia\Models\District', 'id', 'district_id');
    }

    public function village()
    {
        return $this->hasOne('App\Modules\Indonesia\Models\Village', 'id', 'village_id');
    }

    public function appointment()
    {
        return $this->hasMany('App\Modules\Patient\Models\Appointment', 'patient_id', 'id');
    }

    public function treatment()
    {
        return $this->hasMany('App\Modules\Patient\Models\Treatment', 'patient_id', 'id');
    }

    public function timeline()
    {
        return $this->hasMany('App\Modules\Patient\Models\PatientTimeline', 'patient_id', 'id');
    }

    public function media()
    {
        return $this->hasMany('App\Modules\Patient\Models\PatientMediaInfo', 'patient_id', 'id');
    }

    public function mediaInfo()
    {
        return $this->belongsToMany('App\Modules\Patient\Models\PatientMediaInfo', 'patient_media_info', 'patient_id', 'media_id');
    }

    public function beforeafter()
    {
        return $this->hasMany('App\Modules\Patient\Models\PatientBeforeAfter', 'patient_id', 'id');
    }

    public function membership()
    {
        return $this->hasOne('App\Modules\Crm\Models\CrmMembership', 'patient_id', 'id');
    }

    protected $fillable = [
    	'name',
    	// 'admission_date',
    	'patient_name',
    	'birth_place',
    	'age',
    	'age_range',
    	'dob',
    	'phone_number',
    	'wa_number',
    	'email',
    	'gender',
    	'religion_id',
    	'blood_id',
    	'address',
        'city_id',
        'district_id',
        'village_id',
        'zip_code',
        'info_id',
        'work_id',
        'patient_flag_id',
        'old_patient',
        'hobby',
        'last_wa',
        'id_klinik'
    ];

    public static function autocomplete()
    {
        $data = self::get();

        $result = array();

        if(!empty($data)){
            foreach ($data as $key => $value) {
                $result[] = $value->patient_unique_id.' - '.$value->patient_name;
            }
        }

        return $result;
    }

    public function lastWa()
    {
        $this->last_wa = \Carbon\Carbon::now()->format('Y-m-d H:i:s');

        return $this->save();
    }
}
