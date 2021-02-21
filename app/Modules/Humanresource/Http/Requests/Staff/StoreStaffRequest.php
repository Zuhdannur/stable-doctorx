<?php

namespace App\Modules\Humanresource\Http\Requests\Staff;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create staff')) {
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
            'employee_id'    => ['required', 'max:64', Rule::unique('staff')],
            'department_id'    => ['required'],
            'designation_id'    => ['required'],
            'date_of_joining'    => ['required'],
            'gender'    => ['required'],
            'phone_number'    => ['digits_between:10,13', Rule::unique('staff')],
            'full_name'     => ['required', 'max:191'],
            'username'    => ['required', 'max:64', Rule::unique('users')],
            'email'    => ['required', 'email', 'max:191', Rule::unique('users')],
            'password' => ['required', 'min:6'],
            'role_id' => ['required'],
        ];
    }
}
