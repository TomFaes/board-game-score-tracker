<?php

namespace App\Validators;

use Illuminate\Http\Request;

class GroupValidation extends Validation {

    public function validateGroup(Request $request, $id = 0){
        return $this->validate(
            $request,
            [
                'name' => 'required',
            ],
            [
                'name.required' => 'Name is required',
            ]
        );
    }









}
