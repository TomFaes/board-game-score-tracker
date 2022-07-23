<?php

namespace App\Repositories;

use App\Models\PlayedGameScore;
use App\Repositories\Contracts\IPlayedGameScore;

class PlayedGameScoreRepo extends Repository implements IPlayedGameScore
{
    public function getPlayedGameScores($itemsPerPage = 0)
    {
        if ($itemsPerPage > 0){
             return PlayedGameScore::with(['playedGame', 'groupUser', 'groupUser.user'])->paginate($itemsPerPage);
        }
        return PlayedGameScore::with(['playedGame', 'groupUser', 'groupUser.user'])->get();
    }

    public function getPlayedGameScore($id)
    {
        return PlayedGameScore::with(['playedGame', 'groupUser', 'groupUser.user'])->find($id);
    }

    public function getUserPlayedGameScores($groupUserId, $itemsPerPage = 0)
    {
        if ($itemsPerPage > 0){
             return PlayedGameScore::with(['playedGame', 'groupUser', 'groupUser.user'])->where('group_user_id', $groupUserId)->paginate($itemsPerPage);
        }
        return PlayedGameScore::with(['playedGame', 'groupUser', 'groupUser.user'])->where('group_user_id', $groupUserId)->get();
    }

    /***************************************************************************
     Next function will create or update the user object in de database
     **************************************************************************/
    protected function setPlayedGameScore(PlayedGameScore $score, array $data)
    {
        isset($data['group_user_id']) === true ? $score->group_user_id = $data['group_user_id'] : "";
        isset($data['played_game_id']) === true ? $score->played_game_id = $data['played_game_id'] : "";
        isset($data['score']) === true ? $score->score = $data['score'] : "";
        isset($data['place']) === true ? $score->place = $data['place'] : "";
        isset($data['remarks']) === true ? $score->remarks = $data['remarks'] : $score->remarks = NULL;
        return $score;
    }

    protected function determenPlaces(Array $gameScores)
    {
        //sort games from high to low
        uasort($gameScores, function ($a, $b){
            return $b['score'] <=> $a['score'];
        });

        $value = 0;
        $place = 1;

        foreach ($gameScores AS $key => $score){
            if ($score['score']  == 0 && $score['place'] == 0){
                continue;
            }

            if ($score['place'] == 0){
                if ($score['score'] < $value){
                    $place++;
                    $gameScores[$key]['place'] = $place;
                } elseif ($score['score'] == $value){
                    $gameScores[$key]['place'] = $place;
                } elseif ($score['score'] > $value){
                    $gameScores[$key]['place'] = $place;
                }
                $value = $score['score'];
            } else{
                $place = $score['place'];
                $value = $score['score'];
            }
        }
        return $gameScores;
    }

    public function create(Array $data)
    {
        $score = new PlayedGameScore();
        $score = $this->setPlayedGameScore($score, $data);
        $score->save();
        return $score;
    }

    public function createSetScores(Array $gameScores, $playedGameId)
    {
        $gameScores = $this->determenPlaces($gameScores);
        $winnerId = 0;
        foreach ($gameScores AS $gamescore){
            if ($gamescore['score'] > 0 || $gamescore['place'] > 0){
                //set winner
                if ($gamescore['place'] == 1){
                    $winnerId = $gamescore['group_user_id'];
                }
                $gamescore['played_game_id'] = $playedGameId;
                //create a new score line
                $this->create($gamescore);
            }
        }
        return $winnerId;
    }

    public function update(Array $data, $id)
    {
        $score = $this->getPlayedGameScore($id);
        $score = $this->setPlayedGameScore($score, $data);
        $score->save();
        return $score;
    }

    public function updateSetScore(Array $gameScores, $playedGameId)
    {
        $gameScores = $this->determenPlaces($gameScores);
        $winnerId = 0;

        foreach ($gameScores AS $gamescore){
            //set winner
            if ($gamescore['place'] == 1){
                $winnerId = $gamescore['group_user_id'];
            }
            $score = $this->getPlayedGameScore($gamescore['id']);

            //if score is found, update/delete the score
            if ($score != ""){
                if ($gamescore['score'] == 0 &&  $gamescore['place'] == 0){
                    $this->delete($gamescore['id']);
                } else{
                    $score = $this->setPlayedGameScore($score, $gamescore);
                    $score->save();
                }
            } else{
                //if there is a score or place create new line
                if ($gamescore['score'] > 0 ||  $gamescore['place'] > 0){
                    $gamescore['played_game_id'] = $playedGameId;
                    $this->create($gamescore);
                }
            }
        }
        return $winnerId;
    }

    public function delete($id)
    {
        $score = $this->getPlayedGameScore($id);
        return $score->delete();
    }
}
