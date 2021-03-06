<?php

namespace Nhitrort90\CMS\Requests;

//use App\Http\Requests\Request; Ya no lo usa
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name'      => 'required',
            'email'     => 'email|required',
            'type'      => 'required',
            'avatar'    => 'image'
        ];
    }
}
