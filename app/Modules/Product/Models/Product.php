<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Product\Models\Traits\Attribute\ProductAttribute;

class Product extends Model
{
	use ProductAttribute;

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
        'code',
        'name',
        'category_id',
        'price',
        'quantity',
        'purchase_price',
        'min_stock',
        'purchase_price_avg',
        'is_min_stock',
        'sales_type', //0 by purchase_price, 1 by  purchase_price_avg
        'percentage_price_sales',
        'is_active',
        'point',
        'min_qty',
        'id_klinik'
    ];

    protected $appends = ['type'];

    public function getTypeAttribute()
    {
        return 'product';
    }

    public function category()
    {
        return $this->hasOne('App\Modules\Product\Models\ProductCategory', 'id', 'category_id');
    }

    public function autocomplete()
    {
        $data = self::get();

        $result = array();

        if(!empty($data)){
            foreach ($data as $key => $value) {
                $result[] = $value->code.' - '.$value->name.' - '.$value->category->name;
            }
        }

        return $result;
    }

    public function optionList()
    {
        $data = self::get();

        $option = '<option></option>';
        if(!empty($data)){
            foreach ($data as $key => $val) {
                $productName = $val->code.' - '.$val->name.' - '.$val->category->name;
                $option .= '<option value="'.$val->id.'">'.strtoupper($productName).'</option>';
            }
        }

        return $option;
    }

    public function optionListWithPurchasePrice()
    {
        $data = self::get();

        $option = '<option></option>';
        foreach ($data as $key => $val) {
            $productName = $val->code.' - '.$val->name.' - '.$val->category->name;
        	$option .= '<option value="'.$val->id.'#'.strtolower($val->type).'#'.$productName.'" data-type="'.strtolower($val->type).'" data-purchase-price="'.$val->purchase_price.'">'.strtoupper($productName).'</option>';
        }

        return $option;
    }
}
