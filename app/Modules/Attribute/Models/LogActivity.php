<?php

namespace App\Modules\Attribute\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
            $model->user_id = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
        });
    }

    protected $fillable = [
        'module_id',
        'action',
        'desc'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\Auth\User', 'id','user_id');
    }
}
