<?php

namespace App\Http\Requests\Backend\Auth\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreUserRequest.
 */
class StoreUserRequest extends FormRequest
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
            'full_name'     => ['required', 'max:191'],
            'username'    => ['required', 'max:64', Rule::unique('users')],
            'email'    => ['required', 'email', 'max:191', Rule::unique('users')],
            'password' => ['required', 'min:6', 'confirmed'],
            'roles' => ['required', 'array'],
        ];
    }
}
