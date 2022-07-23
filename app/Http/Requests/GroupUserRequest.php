<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupUserRequest extends FormRequest
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
                'firstname' => 'required',
                'name' => 'required',
                'group_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
                'firstname.required' => 'Firstname is required',
                'name.required' => 'Name is required',
                'group_id.required' => 'Group is required',
                'group_id.integer' => 'Group id is required',
        ];
    }
}
