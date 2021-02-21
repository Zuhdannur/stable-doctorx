<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Product\Models\ProductPackage;
use App\Modules\Product\Models\Traits\Attribute\ProductPackageDetailAttribute;

class ProductPackageDetail extends Model
{
    use ProductPackageDetailAttribute;
    
    public $timestamps = false;

    public $table = 'product_package_details';

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

    protected $fillable = ['product_package_id', 'product_id', 'qty'];

    public function package()
    {
        return $this->belongsToMany(ProductPackage::class);
    }

    public function products()
    {
        return $this->hasOne('App\Modules\Product\Models\Product', 'product_id', 'id');
    }
}
