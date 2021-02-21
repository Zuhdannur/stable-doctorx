<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogFrontEnd extends Model
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
        'ip_address', 
        'agent',
    	'request_path', 
    	'request_uri', 
    	'request_full_uri',
    ];

}
