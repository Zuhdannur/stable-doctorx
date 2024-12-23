<?php

namespace App\Http\Requests\Backend\Auth\Role;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreAbilityRequest.
 */
class StoreAbilityRequest extends FormRequest
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
            'name' => ['required', 'unique:abilities', 'max:191'],
        ];
    }
}
