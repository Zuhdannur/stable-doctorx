<?php

namespace App\Modules\Room\Http\Requests\RoomGroup;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\GeneralException;

class ManageRoomGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('view room group')) {
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
