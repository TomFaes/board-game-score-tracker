<?php

namespace App\Validators;

use Illuminate\Http\Request;

class UserValidation extends Validation {

    public function validateUser(Request $request, $id = 0){
        return $this->validate(
            $request,
            [
                'firstname' => 'required',
                'name' => 'required',
                'email' => 'required||unique:users,email,'.$id,
            ],
            [
                'firstname.required' => 'Firstname is required',
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'email has been taken already',
            ]
        );
    }









}
