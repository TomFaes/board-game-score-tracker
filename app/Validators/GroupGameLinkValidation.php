<?php

namespace App\Validators;

use Illuminate\Http\Request;

class GroupGameLinkValidation extends Validation {

    public function validateGroupGameLink(Request $request, $id = 0)
    {

        /*
        $regex = '_^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:.\d{1,3}){3})(?!(?:169.254|192.168)(?:.\d{1,3}){2})(?!172.(?:1[6-9]|2\d|3[0-1])(?:.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-)[a-z\x{00a1}-\x{ffff}0-9]+)(?:.(?:[a-z\x{00a1}-\x{ffff}0-9]-)[a-z\x{00a1}-\x{ffff}0-9]+)(?:.(?:[a-z\x{00a1}-\x{ffff}]{2,})).?)(?::\d{2,5})?(?:[/?#]\S)?$_iuS';

        $rules = array('hewit' => array('required', 'regex:'.$regex));

        $messages = [
            'hewit.required' => 'enter the url you want to hew',
            'hewit.regex' => 'your url have to be a valid url' . "<br/>" . "<span class='val_error_small'>". '(try putting http:// or https:// or another prefix at the beginning)'. "</span>",
        ];
        */


        return $this->validate(
            $request,
            [
                'group_game_id' => 'required',
                'name' => 'required',
                'link' => 'required',
            ],
            [
                'group_game_id.required' => 'Group game id is required',
                'name.required' => 'Link name is required',
                'link.required' => 'Link is required',
            ]
        );
    }
}
