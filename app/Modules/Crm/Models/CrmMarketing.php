<?php

namespace App\Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Crm\Models\Traits\Attribute\MarketingAttributes;

class CrmMarketing extends Model
{
    use SoftDeletes;
    use MarketingAttributes;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
            $model->created_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
            $model->updated_by = auth()->user()->id;
        });
    }

    protected $fillable = [
        'code',
        'name',
        'start_date',
        'end_date',
        'discount',
        'point',
        'is_active',
    ];

    public static function generateCode()
    {
        $count = self::count();

        if($count < 0){
            return 'MA-1';
        }else{
            return 'MA-'.$count;
        }
    }

    public function invoice()
    {
        return $this->hasMany('App\Modules\Billing\Model\Billing', 'id', 'marketing_id');
    }
}
