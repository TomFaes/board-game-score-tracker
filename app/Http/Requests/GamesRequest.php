<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GamesRequest extends FormRequest
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
            'name' => 'required',
            'year' => 'nullable|integer',
            'players_min' => 'nullable|integer',
            'players_max' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'year.integer' => 'Year must be a number',
            'players_min.integer' => 'Players minimum must be a number',
            'players_max.integer' => 'Players maximum must be a number',
        ];
    }
}
