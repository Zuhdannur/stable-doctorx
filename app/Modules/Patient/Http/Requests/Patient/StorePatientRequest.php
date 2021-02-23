<?php

namespace App\Modules\Patient\Http\Requests\Patient;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use App\Exceptions\GeneralException;

class StorePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'blood_id'    => ['required'],
            'gender'    => ['required'],
            'phone_number'    => ['required', 'regex:/[0-9]/', Rule::unique('patients')],
            'wa_number'    => ['required', 'regex:/[0-9]/', Rule::unique('patients')],
            'patient_name'     => ['required', 'max:64'],
            'email'    => ['email', 'max:191', Rule::unique('patients')],
            'address' => ['required'],
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
