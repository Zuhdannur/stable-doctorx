<?php

namespace App\Modules\Humanresource\Http\Requests\Designation;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDesignationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create designation')) {
            throw new GeneralException(trans('exceptions.cannot_create'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    => ['required', 'max:64', Rule::unique('staff_designations')],
        ];
    }
}
