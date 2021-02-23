<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentDetail extends Model
{
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
        'treatment_id', 
        'name', 
        'description',
    ];
}
