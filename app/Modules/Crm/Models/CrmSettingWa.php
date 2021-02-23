<?php

namespace App\Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class CrmSettingWa extends Model
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
        'name',
        'api_url',
        'settings',
        'wa_message',
        'wa_message_reminder',
        'is_active',
    ];

    // get data for Woo Wa
    public static function getSettingsWooWa()
    {
        return self::where('id', 1)->first();
    }

    // get data for Twillio
    public static function getSettingsTwiilio()
    {
        return self::where('id', 2)->first();
    }
}
