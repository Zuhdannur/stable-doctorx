<?php

namespace App\Modules\Product\Http\Requests\Supplier;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_name' => [
                'required', 
                'max:100',
            ],
            'gender' => [
                'required', 
                'max:2'
            ],
            'phone_number' => [
                'required', 
                'max:20',
                'regex:/[0-9]/'
            ],
            'email' => [
                'required',
                'max:50'
            ],
            'birth_place' => [
                'required',
                'max:75'
            ],
            'dob' => [
                'required',
            ],
            'company_name' => [
                'required',
                'max:100'
            ],
            'company_phone_number' => [
                'required',
                'max:150'
            ],
            'company_address' => [
                'required',
                'max:150'
            ],
            'company_city_id' => [
                'required',
            ],
            'company_district_id' => [
                'required',
            ],
            'company_village_id' => [
                'required',
            ],
        ];
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        if ($this->expectsJson()) {
            return new JsonResponse($errors, 403);
        }
    }
}
