<?php

namespace App\Modules\Humanresource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Humanresource\Models\Traits\Attribute\StaffAttribute;

class Staff extends Model
{
    use StaffAttribute, SoftDeletes;

    public $timestamps = false;

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

    public function setDateOfBirthAttribute($value)
	{
	    $this->attributes['date_of_birth'] = \DateTime::createFromFormat(setting()->get('date_format'), $value)->format('Y-m-d');
	}

    public function setDateOfJoiningAttribute($value)
	{
	    $this->attributes['date_of_joining'] = \DateTime::createFromFormat(setting()->get('date_format'), $value)->format('Y-m-d');
	}

    public function getDateOfBirthFormatedAttribute()
    {
        return \DateTime::createFromFormat('Y-m-d', $this->attributes['date_of_birth'])->format(setting()->get('date_format'));
    }

    public function getDateOfJoiningFormatedAttribute()
    {
        return \DateTime::createFromFormat('Y-m-d', $this->attributes['date_of_joining'])->format(setting()->get('date_format'));
    }

    protected $fillable = [
    	'employee_id', 
    	'department_id', 
    	'designation_id', 
    	'phone_number',
    	'religion_id', 
    	'blood_id', 
    	'address', 
    	'place_of_birth', 
    	'date_of_birth', 
    	'marital_status', 
    	'date_of_joining', 
    	'gender', 
    	'note', 
    	'old_patient', 
    	'user_id'
    ];

    public function department()
    {
        return $this->hasOne('App\Modules\Attribute\Models\AttributeDepartment', 'id', 'department_id');
    }

    public function designation()
    {
        return $this->hasOne('App\Modules\Humanresource\Models\StaffDesignation', 'id', 'designation_id');
    }

    public function religion()
    {
        return $this->hasOne('App\Modules\Attribute\Models\AttributeReligion', 'id', 'religion_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\Auth\User', 'id', 'user_id');
    }

    public function staffIncentive()
    {
        return $this->belongsToMany('App\Modules\Incentive\Models\IncentiveStaff', 'incentive_staff', 'staff_id', 'incentive_id');
    }
}
