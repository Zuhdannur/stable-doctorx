<?php

namespace App\Modules\Product\Repositories;

use App\Helpers\Auth\Auth;
use App\Modules\Product\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class ProductCategoryRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return ProductCategory::class;
    }

    public function create(array $data) : ProductCategory
    {
        if(!$data['parent_id']){
            if ($this->checkExists($data['name'])) {
                throw new GeneralException(trans('product::exceptions.category.already_exists') . ' - '.$data['name']);
            }
        }

        return DB::transaction(function () use ($data) {
            $status = isset($data['is_active']) ? 1 : 0;
            $create = parent::create([
                'name'      => $data['name'],
                'parent_id' => $data['parent_id'],
                'is_active' => $status,
                'id_klinik' => Auth()->user()->klinik->id_klinik
            ]);

            if ($create) {

                return $create;
            }

            throw new GeneralException(trans('product::exceptions.category.create_error'));
        });
    }

    public function update(ProductCategory $productCategory, array $data)
    {
        if(!$data['parent_id'] && strtolower($productCategory->name) !== strtolower($data['name'])){
            if ($this->checkExists($data['name'])) {
                throw new GeneralException(trans('product::exceptions.category.already_exists') . ' - '.$data['name']);
            }
        }

        return DB::transaction(function () use ($productCategory, $data) {
            $status = isset($data['is_active']) ? 1 : 0;

            $productCategory = $productCategory->update([
                'name'      => $data['name'],
                'parent_id' => $data['parent_id'],
                'is_active' => $status
            ]);

            if ($productCategory) {

                return $productCategory;
            }

            throw new GeneralException(trans('product::exceptions.category.update_error'));
        });
    }

    protected function checkExists($name) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->whereNull('parent_id')
                ->count() > 0;
    }
}
