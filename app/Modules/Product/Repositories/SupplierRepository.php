<?php

namespace App\Modules\Product\Repositories;

use App\Modules\Product\Models\Supplier;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;

class SupplierRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Supplier::class;
    }

    public function create(array $data) : Supplier
    {
        return DB::transaction(function () use($data){
            $supplier = Supplier::create([
                'supplier_name' => $data['supplier_name'],
                'birth_place'   => $data['birth_place'],
                'dob'   => $data['dob'],
                'gender'    => $data['gender'],
                'phone_number'  => $data['phone_number'],
                'email' => $data['email'],
                'company_name'  => $data['company_name'],
                'company_phone_number'  => $data['company_phone_number'],
                'company_city_id'   => $data['company_city_id'],
                'company_district_id'    => $data['company_district_id'],
                'company_village_id'    => $data['company_village_id'],
                'company_address'   => $data['company_address'],
                'id_klinik' => auth()->user()->id_klinik
            ]);

            if($supplier){
                $code = Supplier::generateCode();

                $supplier->supplier_code = $code;

                $supplier->save();

                return $supplier;
            }

            throw new GeneralException(trans('product::exceptions.supplier.create_error'));

        });
    }

    public function update(array $data)
    {
        $supplier = Supplier::findOrFail($data['id']);

        $supplier->supplier_name    = $data['supplier_name'];
        $supplier->birth_place      = $data['birth_place'];
        $supplier->dob              = $data['dob'];
        $supplier->gender           = $data['gender'];
        $supplier->phone_number     = $data['phone_number'];
        $supplier->email            = $data['email'];
        $supplier->company_name     = $data['company_name'];
        $supplier->company_phone_number     = $data['company_phone_number'];
        $supplier->company_city_id          = $data['company_city_id'];
        $supplier->company_district_id      = $data['company_district_id'];
        $supplier->company_village_id       = $data['company_village_id'];
        $supplier->company_address          = $data['company_address'];

        if(!$supplier->save()){
            throw new GeneralException(trans('product::exceptions.supplier.update_error'));
        }

        return $supplier;
    }
}
