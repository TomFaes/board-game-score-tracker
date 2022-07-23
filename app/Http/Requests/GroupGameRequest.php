<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupGameRequest extends FormRequest
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
                'group_id' => 'required|integer',
                'game_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'group_id.required' => 'Group is required',
            'game_id.required' => 'Game is required',

            'group_id.integer' => 'Group id is required',
            'game_id.integer' => 'Game id is required',
        ];
    }
}
