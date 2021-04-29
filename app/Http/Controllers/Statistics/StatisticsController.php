<?php

namespace App\Http\Controllers\Statistics;
use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use App\Repositories\PlayedGameRepo;
use App\Services\StatisticsService\StatisticsFactory;

class StatisticsController extends Controller
{

    public function groupStats($groupId)
    {
        $repo = new PlayedGameRepo();
        $playedGames = $repo->getStatPlayedGroupGames($groupId);

        $statisticsGenerator = StatisticsFactory::generate("GroupStatistics");
        $data = $statisticsGenerator->getAll($playedGames);

        return response()->json($data, 200);
    }

    public function groupYearStats($groupId, $year)
    {
        $repo = new PlayedGameRepo();

        $playedGames = $repo->getStatPlayedGroupYearGames($groupId, $year);

        $statisticsGenerator = StatisticsFactory::generate("GroupStatistics");
        $data = $statisticsGenerator->getAll($playedGames);

        return response()->json($data, 200);
    }

    public function groupGameStats($groupId, $gameId){
        $repo = new PlayedGameRepo();
        $playedGames = $repo->getStatPlayedGame($groupId, $gameId);

        $statisticsGenerator = StatisticsFactory::generate("GroupStatistics");
        $data = $statisticsGenerator->getAll($playedGames);
        return response()->json($data, 200);
    }
}
