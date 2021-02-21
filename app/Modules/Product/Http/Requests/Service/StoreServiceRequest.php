<?php

namespace App\Modules\Product\Http\Requests\Service;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'code' => [
                'required', 
                'max:12',
                Rule::unique('products')
            ],
            'name' => [
                'required', 
                'max:64'
            ],
            'category_id' => [
                'required', 
                'max:64'
            ]
        ];
    }
}
