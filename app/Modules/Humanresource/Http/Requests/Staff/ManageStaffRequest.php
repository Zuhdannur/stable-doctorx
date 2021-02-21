<?php

namespace App\Modules\Humanresource\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ManageStudentRequest.
 */
class ManageStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('view staff')) {
            throw new GeneralException(trans('exceptions.cannot_view'));
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
            //
        ];
    }
}
