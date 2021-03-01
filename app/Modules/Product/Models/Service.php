<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Product\Models\ServicesPackage;
use App\Modules\Product\Models\Traits\Attribute\ServiceAttribute;

class Service extends Model
{
	use ServiceAttribute;

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

    protected $fillable = ['code', 'name', 'category_id', 'price', 'is_active','point' ,'flag','id_klinik'];

    protected $appends = ['type'];

    public function getTypeAttribute()
    {
        return 'service';
    }

    public function category()
    {
        return $this->hasOne('App\Modules\Product\Models\ServiceCategory', 'id', 'category_id');
    }

    public function autocomplete()
    {
        $data = self::get();

        $result = array();

        if(!empty($data)){
            foreach ($data as $key => $value) {
                $result[] = $value->code.' - '.$value->name.' - '.$value->category->name;
            }
        }

        return $result;
    }

    public function optionList()
    {
        $data = self::get();

        $option = '<option></option>';
        if(!empty($data)){
            foreach ($data as $key => $val) {
                $servicetName = $val->code.' - '.$val->name.' - '.$val->category->name;
                $option .= '<option value="'.$val->id.'">'.strtoupper($servicetName).'</option>';
            }
        }

        return $option;
    }

    public function optionListWithKlinik()
    {
        $data = self::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();

        $option = '<option></option>';
        if(!empty($data)){
            foreach ($data as $key => $val) {
                $servicetName = $val->code.' - '.$val->name.' - '.$val->category->name;
                $option .= '<option value="'.$val->id.'">'.strtoupper($servicetName).'</option>';
            }
        }

        return $option;
    }

    public function optionListWithPackages()
    {
        $data = self::where('id_klinik',Auth()->user()->klinik->id_klinik)->get();

        $option = '<option></option>';
        if(!empty($data)){
            foreach ($data as $key => $val) {
                $servicetName = $val->code.' - '.$val->name.' - '.$val->category->name;
                $option .= '<option value="service#'.$val->id.'">'.strtoupper($servicetName).'</option>';
            }
        }

        $data2 = ServicesPackage::where('is_active',1)->get();
        if(!empty($data2)){
            foreach ($data2 as $key => $val) {
                $servicetName = 'Paket - '.$val->name.' (Paket)';
                $option .= '<option value="servicePackages#'.$val->id.'">'.strtoupper($servicetName).'</option>';
            }
        }

        return $option;
    }

    public function getFlagServiceAttribute() {
        return $this->flag == 1 ? "Dokter" : "Terapis";
    }
}
