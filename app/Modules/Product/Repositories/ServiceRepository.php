<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\Service;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class ServiceRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Service::class;
    }

    public function create(array $data) : Service
    {
        if ($this->checkExists($data['name'], $data['category_id'])) {
            throw new GeneralException(trans('product::exceptions.service.already_exists') . ' - '.$data['name']);
        }

        return DB::transaction(function () use ($data) {
            $status = isset($data['is_active']) ? 1 : 0;
            $create = parent::create([
                'code'      => $data['code'],
                'name'      => $data['name'],
                'category_id' => $data['category_id'],
                'price' => currency()->digit($data['price']),
                'is_active' => $status
            ]);

            if ($create) {

                return $create;
            }

            throw new GeneralException(trans('product::exceptions.service.create_error'));
        });
    }

    public function update(Service $service, array $data)
    {
        if(strtolower($service->name) !== strtolower($data['name']) && $service->category_id !== $data['category_id']){
            if ($this->checkExists($data['name'], $data['category_id'])) {
                throw new GeneralException(trans('product::exceptions.service.already_exists') . ' - '.$data['name']);
            }
        }

        return DB::transaction(function () use ($service, $data) {
            $status = isset($data['is_active']) ? 1 : 0;

            $service = $service->update([
                'code'      => $data['code'],
                'name'      => $data['name'],
                'category_id' => $data['category_id'],
                'price' => currency()->digit($data['price']),
                'is_active' => $status,
                'flag' => $data['flag']
            ]);

            if ($service) {

                return $service;
            }

            throw new GeneralException(trans('product::exceptions.service.update_error'));
        });
    }

    protected function checkExists($name, $category_id) : bool
    {
        return $this->model
                ->where('name', strtolower($name))
                ->where('category_id', $category_id)
                ->count() > 0;
    }

    public function getByCategory($catId){
        return $this->model->whereHas('category', function ($q) use ($catId) {
            $q->where('category_id', $catId);
        })->get();
    }
}
