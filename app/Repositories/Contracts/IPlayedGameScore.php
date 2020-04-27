<?php

namespace App\Repositories\Contracts;

interface IPlayedGameScore {
    public function getPlayedGameScores();
    public function getPlayedGameScore($id);
    public function getScorePlayedGame($playedGameId);
    public function getUserPlayedGameScores($userId);

    public function create(Array $data);
    public function createSetScores(Array $gameScores, $playedGameId);
    public function update(Array $data, $id);
    public function updateSetScore(Array $gameScores, $playedGameId);
    public function delete($id);
}
