<?php

namespace App\Modules\Crm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Modules\Crm\Models\Traits\Attribute\MembershipAttributes;

class CrmMsMembership extends Model
{
    use SoftDeletes;
    use MembershipAttributes;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
            // $model->created_by = auth()->user()->id;
        });

        static::updating(function ($model) {
            $model->updated_at = $model->freshTimestamp();
            // $model->updated_by = auth()->user()->id;
        });
    }

    protected $fillable = [
        'name',
        'point',
        'min_trx',
    ];

    public static function optionList()
    {
        $data = self::orderBy('name', 'asc')->get();
        $list = '<option></option>';
        foreach ($data as $val) {
            $list .= '<option value="'.$val->id.'">'.$val->name.'</option>';
        }

        return $list;
    }

    function membershipCategory(){
        return $this->hasManyThrough(
            'App\Modules\Crm\Models\CrmRadeemPoint','App\Modules\Crm\Models\CrmMembership',
            'membership_id',
            'ms_membership_id',
            'id'
        );
    }
}
