<?php

namespace App\Services\StatisticsService;

interface IStatistic {
    public function getAll($playedGames);
    public function getScores($playedGames);
    public function getPositions($playedGames);
    public function getVictories($playedGames);
}
