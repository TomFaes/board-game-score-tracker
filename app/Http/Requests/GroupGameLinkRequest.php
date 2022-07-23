<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupGameLinkRequest extends FormRequest
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
                'group_game_id' => 'required',
                'name' => 'required',
                'link' => 'required',
                'description' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'group_game_id.required' => 'Group game id is required',
            'name.required' => 'Link name is required',
            'link.required' => 'Link is required',
        ];
    }
}
