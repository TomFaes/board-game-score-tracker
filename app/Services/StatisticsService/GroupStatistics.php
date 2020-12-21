<?php

namespace App\Services\StatisticsService;

use App\Models\PlayedGame;
use App\Services\StatisticsService\IStatistic;

/**
 * This factory will return the statistics of a group
 */
class GroupStatistics implements IStatistic
{

    public function getAll($playedGames)
    {
        $i = 0;
        $data = array();
        foreach ($playedGames AS $playedGame) {
            $i++;
            $data['data'][$i]['name'] = $playedGame->game->name;
            $data['data'][$i]['date'] = $playedGame->date;
            $data['data'][$i]['expansions'] = $playedGame->expansions;



            foreach ($playedGame->group->groupUsers AS $groupUser) {
                $id =  $groupUser->id;
                $data['data'][$i]['users'][$id]['name'] = $groupUser->full_name;
                $data['data'][$i]['users'][$id]['place'] = "";
                $data['data'][$i]['users'][$id]['score'] = "";
                $data['data'][$i]['users'][$id]['victory'] = 0;

                isset($data['total']['place'][$id]) === false ? $data['total']['place'][$id] = 0 : "";
                isset($data['total']['victory'][$id]) === false ?  $data['total']['victory'][$id] = 0 : "";
            }

            foreach ($playedGame->scores AS $score) {
                $id =  $score->group_user_id;
                $data['data'][$i]['users'][$id]['place'] = $score->place;
                $data['data'][$i]['users'][$id]['score'] = $score->score;

                $data['total']['place'][$id] += $score->place;

                if( $score->place == 1){
                    $data['data'][$i]['users'][$id]['victory']++;
                    $data['total']['victory'][$id]++;
                }
            }
            //in this case only 1 person could be the winner
            //$winnerId =  $playedGame->winner_id;
            //$data['data'][$i]['users'][$winnerId]['victory']++;
            //$data['total']['victory'][$winnerId]++;
        }
        return $data;
    }

    public function getScores($playedGames)
    {
        $data = array();
        $i = 0;
        foreach ($playedGames AS $playedGame) {
            $i++;
            $data[$i]['name'] = $playedGame->game->name;
            $data[$i]['date'] = $playedGame->date;

            foreach ($playedGame->group->groupUsers AS $groupUser) {
                $id =  $groupUser->id;
                $data[$i]['users'][$id]['name'] = $groupUser->full_name;
                $data[$i]['users'][$id]['score'] = "";
            }

            foreach ($playedGame->scores AS $score) {
                $id =  $score->group_user_id;
                $data[$i]['users'][$id]['score'] = $score->score;
            }
        }
        return $data;

    }

    public function getPositions($playedGames)
    {
        $data = array();
        $i = 0;
        foreach ($playedGames AS $playedGame) {
            $i++;
            $data[$i]['name'] = $playedGame->game->name;
            $data[$i]['date'] = $playedGame->date;

            foreach ($playedGame->group->groupUsers AS $groupUser) {
                $id =  $groupUser->id;
                $data[$i]['users'][$id]['name'] = $groupUser->full_name;
                $data[$i]['users'][$id]['place'] = "";

                isset($data['total']['place'][$id]) === false ? $data['total']['place'][$id] = 0 : "";
            }

            foreach ($playedGame->scores AS $score) {
                $id =  $score->group_user_id;
                $data[$i]['users'][$id]['place'] = $score->place;

                $data['total']['place'][$id] += $score->place;
            }
        }
        return $data;
    }

    public function getVictories($playedGames)
    {
        $data = array();
        $i = 0;
        foreach ($playedGames AS $playedGame) {
            $i++;
            $data[$i]['name'] = $playedGame->game->name;
            $data[$i]['date'] = $playedGame->date;

            foreach ($playedGame->group->groupUsers AS $groupUser) {
                $id =  $groupUser->id;
                $data[$i]['users'][$id]['name'] = $groupUser->full_name;
                $data[$i]['users'][$id]['victory'] = 0;

                isset($data['total']['victory'][$id]) === false ?  $data['total']['victory'][$id] = 0 : "";
            }

            $winnerId =  $playedGame->winner_id;
            $data[$i]['users'][$winnerId]['victory']++;
            $data['total']['victory'][$winnerId]++;
        }
        return $data;
    }
}
