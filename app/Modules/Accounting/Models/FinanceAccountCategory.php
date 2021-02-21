<?php

namespace App\Modules\Accounting\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Accounting\Models\Traits\Attribute\FinanceAccountCategoryAttribute;

class FinanceAccountCategory extends Model
{

    use FinanceAccountCategoryAttribute;
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
        'category_code',
        'category_name',
        'parent_id',
        'type'
    ];

    public function parent()
    {
    	return $this->hasOne(self::class, 'id', 'parent_id');
    }

    public static function getMsCategories()
    {
        return self::where('parent_id', '<', 1)->get();
    }
}
