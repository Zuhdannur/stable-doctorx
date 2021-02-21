<?php

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class ProductCategory extends Model
{
	use NodeTrait;

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

    protected $fillable = ['name', 'is_active', 'parent_id','id_klinik'];

    public function getLftName()
	{
	    return 'left';
	}

	public function getRgtName()
	{
	    return 'right';
	}

	public function getParentIdName()
	{
	    return 'parent_id';
	}

	// Specify parent id attribute mutator
	public function setParentAttribute($value)
	{
	    $this->setParentIdAttribute($value);
	}

    public function buildTree($elements, $key = 'parent_id', $parentId = 0) {
	    $branch = array();

        foreach ((array)$elements as $element) {
        	die(print_r($element));
            if ($element[$key] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
	}

	public function flatten($elements, $depth = 0, $keyChild = 'children', $symbol = '— ') {
	    $result = array();

	    foreach ($elements as $key => $element) {
	        $element['depth'] = $depth;

	        if (isset($element['children'])) {
	            $children = $element['children'];
	            unset($element['children']);
	        } else {
	            $children = null;
	        }

	        $prefix = str_repeat($symbol, $depth);
	        $element['name'] = $prefix . $element['name'];
	        $result[] = $element;

	        if (isset($children)) {
	            $result = array_merge($result, $this->flatten($children, $depth + 1, $keyChild, $symbol));
	        }
	    }

	    return $result;
	}
}
