<?php

namespace App\Validators;

use Illuminate\Http\Request;

class PlayedGameValidation extends Validation {

    public function validatePlayedGame(Request $request, $id = 0){
        return $this->validate(
            $request,
            [
                'group_id' => 'required',
                'game_id' => 'required',
                'date' => 'nullable|date',
                'time' => 'nullable|regex:/(\d+\:\d+)/',
            ],
            [
                'group_id.required' => 'A group is required',
                'game_id.required' => 'A game is required',
                'date.date' => 'This is not a valid date',
                'time.regex' => 'This is not a valid time',
            ]
        );
    }









}
