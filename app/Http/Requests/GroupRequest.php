<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
            'admin_id' => 'nullable|numeric',
            'description' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'admin_id.numeric' => ' No admin selected',
        ];
    }
}
