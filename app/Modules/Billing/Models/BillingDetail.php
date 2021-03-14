<?php

namespace App\Modules\Billing\Models;

use App\Modules\Product\Models\Product;
use App\Modules\Product\Models\Service;
use Illuminate\Database\Eloquent\Model;

class BillingDetail extends Model
{
	protected $table = 'invoice_details';
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
    	'product_id',
        'price',
        'type',
    	'qty',
    	'tax_label',
        'tax_value',
        'payment_step',
        'discount'
    ];

    public function getProductAttribute()
    {
        $type = $this->attributes['type'];
        $id = $this->attributes['product_id'];
        $productName = null;
        if($type == 'product'){
            $source = Product::find($id);
            $productName = $source->code.' - '.$source->name.' - '.$source->category->name;
        }elseif($type == 'service'){
            $source = Service::find($id);
            $productName = $source->code.' - '.$source->name.' - '.$source->category->name;
        }

        return $productName;
    }

    public function getProductNameAttribute()
    {
        $type = $this->attributes['type'];
        $id = $this->attributes['product_id'];
        $productName = null;
        if($type == 'product'){
            $source = Product::find($id);
            $productName = $source->name;
        }elseif($type == 'service'){
            $source = Service::find($id);
            $productName = $source->name;
        }

        return $productName;
    }

    public function getProductDescAttribute()
    {
        $type = $this->attributes['type'];
        $id = $this->attributes['product_id'];
        $productName = null;
        if($type == 'product'){
            $source = Product::find($id);
            $productName = $source->code.' - '.$source->category->name;
        }elseif($type == 'service'){
            $source = Service::find($id);
            $productName = $source->code.' - '.$source->category->name;
        }

        return $productName;
    }

    public function getPriceTaxAttribute(){
        $price = $this->attributes['price'];
        $tax = $this->attributes['tax_value'] / 100;

        return intVal($price + ($price * $tax));
    }

    public function getPointAttribute()
    {
        $type = $this->attributes['type'];
        $id = $this->attributes['product_id'];
        $point = null;
        if($type == 'product'){
            $source = Product::find($id);
            $point = $source->point;
        }elseif($type == 'service'){
            $source = Service::find($id);
            $point =  $source->point;
        }

        return $point;
    }

    public function getProductPriceAttribute() {
        $type = $this->attributes['type'];
        $id = $this->attributes['product_id'];
        $productName = null;
        if($type == 'product'){
            $source = Product::find($id);
            $productName = $source->price;
        }elseif($type == 'service'){
            $source = Service::find($id);
            $productName = $source->price;
        }

        return $productName;
    }
}
