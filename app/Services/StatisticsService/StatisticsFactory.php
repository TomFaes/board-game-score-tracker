<?php

namespace App\Services\StatisticsService;

use Exception;


class StatisticsFactory
{
    public static function generate($type){

        // assumes the use of an autoloader
        $statisticsName = "App\Services\StatisticsService\\".$type;

        if (class_exists($statisticsName)) {
            return new $statisticsName();
        }
        else {
            return response()->json("er is geen type generator gevonden met volgende naam:  ".$type, 200);
        }
    }
}
