<?php

namespace App\Modules\Billing\Http\Requests\Billing;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBillingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create billing')) {
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
            'invoice_no'    => ['max:128', Rule::unique('invoices')],
            'patient_id'    => ['required'],
            'date'    => ['required'],
        ];
    }
}
