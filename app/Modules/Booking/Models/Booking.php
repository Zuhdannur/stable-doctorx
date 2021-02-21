<?php

namespace App\Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
	protected $dates = ['date'];

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
        'type',
        'date',
    ];
}
