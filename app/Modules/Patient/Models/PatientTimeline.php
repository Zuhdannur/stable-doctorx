<?php

namespace App\Modules\Patient\Models;

use Illuminate\Database\Eloquent\Model;
use Str;
use URL;
use Illuminate\Http\UploadedFile;

class PatientTimeline extends Model
{
    public $timestamps = false;

    protected $dates = ['timeline_date', 'created_at', 'updated_at', 'deleted_at'];

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
    	'patient_id', 
    	'title', 
        'timeline_date',
        'description'
    ];

    public function files()
    {
        return $this->hasMany('App\Modules\Patient\Models\PatientTimelineDetail', 'timeline_id', 'id');
    }
}
