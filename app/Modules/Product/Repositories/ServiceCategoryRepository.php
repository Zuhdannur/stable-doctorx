<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\ServiceCategory;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class ServiceCategoryRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return ServiceCategory::class;
    }

    public function create(array $data) : ServiceCategory
    {
        if(!$data['name']){
            if ($this->checkExists($data['name'])) {
                throw new GeneralException(trans('product::exceptions.servicecategory.already_exists') . ' - '.$data['name']);
            }
        }

        return DB::transaction(function () use ($data) {
            $status = isset($data['is_active']) ? 1 : 0;
            $create = parent::create([
                'name'      => $data['name'],
                'is_active' => $status,
                'id_klinik' => Auth()->user()->klinik->id_klinik
            ]);

            if ($create) {

                return $create;
            }

            throw new GeneralException(trans('product::exceptions.servicecategory.create_error'));
        });
    }

    public function update(ServiceCategory $serviceCategory, array $data)
    {
        if(strtolower($serviceCategory->name) !== strtolower($data['name'])){
            if ($this->checkExists($data['name'])) {
                throw new GeneralException(trans('product::exceptions.servicecategory.already_exists') . ' - '.$data['name']);
            }
        }

        return DB::transaction(function () use ($serviceCategory, $data) {
            $status = isset($data['is_active']) ? 1 : 0;

            $serviceCategory = $serviceCategory->update([
                'name'      => $data['name'],
                'is_active' => $status
            ]);

            if ($serviceCategory) {

                return $serviceCategory;
            }

            throw new GeneralException(trans('product::exceptions.servicecategory.update_error'));
        });
    }

    protected function checkExists($name) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->count() > 0;
    }
}
