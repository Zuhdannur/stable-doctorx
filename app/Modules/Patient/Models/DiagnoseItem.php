<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Patient\Models\Traits\Attribute\DiagnoseItemAttribute;

class DiagnoseItem extends Model
{
    use DiagnoseItemAttribute, SoftDeletes;
    
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

    protected $fillable = [
    	'code', 
    	'name', 
    	'description',
    ];

    public function autocomplete()
    {
        $data = self::get();

        $result = array();

        if(!empty($data)){
            foreach ($data as $key => $value) {
                $result[] = $value->name;
            }
        }

        return $result;
    }
}
