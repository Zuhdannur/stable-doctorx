<?php

namespace App\Modules\Room\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\GeneralException;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->user()->cannot('create room')) {
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
            'name' => ['required', 'max:64'],
            'room_group_id' => ['required'],
        ];
    }
}
