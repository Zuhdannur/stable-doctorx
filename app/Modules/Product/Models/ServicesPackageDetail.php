<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ServicesPackageDetail extends Model
{
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
        'service_package_id',
        'service_id',
        'qty'
    ];

    public function service()
    {
        return $this->hasOne('App\Modules\Product\Models\Service', 'id', 'service_id');
    }
}
