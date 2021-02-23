<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Product\Models\ProductPackageDetail;
use App\Modules\Product\Models\Traits\Attribute\ProductPackageAttribute;

class ProductPackage extends Model
{
	use ProductPackageAttribute;

    public $timestamps = false;

    public $table = 'product_packages';

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

    protected $fillable = ['name'];

    public function productpackages()
    {
        return $this->belongsToMany(ProductPackageDetail::class, 'product_package_details', 'product_package_id', 'product_id');
    }

    public function productdetails()
    {
        return $this->hasMany('App\Modules\Product\Models\ProductPackageDetail', 'product_package_id', 'id');
    }
}
