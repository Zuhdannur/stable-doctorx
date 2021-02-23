<?php

namespace App\Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceServiceDetail extends Model
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
    	'invoice_id', 
    	'service_id', 
        'price', 
        'type', 
    	'qty'
    ];
}
