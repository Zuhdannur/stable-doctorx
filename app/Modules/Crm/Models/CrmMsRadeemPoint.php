<?php

namespace App\Modules\Crm\Models;

use App\Modules\Crm\Models\Traits\Attribute\MsRadeemAttributes;
use Illuminate\Database\Eloquent\Model;

class CrmMsRadeemPoint extends Model
{
    use MsRadeemAttributes;
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
        'point',
        'nominal_gift',
    ];

    public static function generateCode()
    {
        $count = self::count();

        if($count > 0){
            return 'R-'.$count;
        }else{
            return 'R-1';
        }
    }

    public static function optionList(){
        $data = self::orderBy('code', 'asc')->get();
        $list = '<option></option>';

        foreach($data as $val){
            $name = $val->code.' - '.currency()->rupiah($val->nominal_gift, setting()->get('currency_symbol')).' ('.$val->point.'pts)';
            $list .= '<option value="'.$val->id.'" data-point="'.$val->point.'" data-gift="'.$val->nominal_gift.'">'
                    .$name.'</option>';
        }

        return $list;
    }
}
