<?php

namespace App\Modules\Humanresource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Humanresource\Models\Traits\Attribute\DepartmentAttribute;

class Department extends Model
{
    use DepartmentAttribute, SoftDeletes;

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
    	'name',
        'description'
    ];
}
