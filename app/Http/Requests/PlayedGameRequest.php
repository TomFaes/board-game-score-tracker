<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlayedGameRequest extends FormRequest
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
            'group_id' => 'required',
            'game_id' => 'required',
            'date' => 'required|date',
            'time_played' => 'nullable|regex:/(\d+\:\d+)/',
            'remarks' => 'nullable',
        ];
    }

    public function messages(){
        return [
            'group_id.required' => 'A group is required',
            'game_id.required' => 'A game is required',
            'date.date' => 'This is not a valid date',
            'time_played.regex' => 'This is not a valid time',
        ];
    }
}
