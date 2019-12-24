<?php

namespace App\Validators;

use Illuminate\Http\Request;

class GameValidation extends Validation {

    public function validateGame(Request $request, $id = 0){
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
