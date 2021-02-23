<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
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

    protected $fillable = ['name', 'is_active','id_klinik'];
}
