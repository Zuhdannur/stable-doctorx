<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Product\Models\Traits\Attribute\SupplierAttribute;

class Supplier extends Model
{
    use SupplierAttribute;

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

    protected $fillable = [
        'supplier_code',
        'supplier_name',
        'birth_place',
        'dob',
        'gender',
        'phone_number',
        'email',
        'company_name',
        'company_phone_number',
        'company_city_id',
        'company_district_id',
        'company_village_id',
        'company_address',
    ];

    protected $dates = ['dob', 'created_at', 'updated_at'];

    public static function optionList()
    {
        $data = self::get();

        $option = '<option></option>';
        if(!empty($data)){
            foreach ($data as $key => $val) {
                $name = $val->supplier_code.' - '.$val->supplier_name.' - '.$val->company_name;
                $option .= '<option value="'.$val->id.'">'.strtoupper($name).'</option>';
            }
        }

        return $option;
    }

    public static function generateCode()
    {
        $count = self::get()->count();

        return 'S-'.$count;

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

    public function getAgeAttribute()
	{
	    return \Carbon\Carbon::parse($this->attributes['dob'])->age;
	}
    
    public function city()
    {
        return $this->hasOne('App\Modules\Indonesia\Models\City', 'id', 'company_city_id');
    }

    public function district()
    {
        return $this->hasOne('App\Modules\Indonesia\Models\District', 'id', 'company_district_id');
    }

    public function village()
    {
        return $this->hasOne('App\Modules\Indonesia\Models\Village', 'id', 'company_village_id');
    }
}
