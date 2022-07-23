<?php

namespace App\Http\Controllers\Statistics;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Group;
use App\Repositories\Contracts\IPlayedGame;
use App\Services\StatisticsService\StatisticsFactory;

class StatisticsController extends Controller
{
    protected $statisticsGenerator;
    protected $playedGameRepo;

    public function __construct(IPlayedGame $playedGameeRepo)
    {
        $this->playedGameRepo = $playedGameeRepo;
        $this->statisticsGenerator = StatisticsFactory::generate("GroupStatistics");
    }

    public function groupStats(Group $group)
    {
        $playedGames = $this->playedGameRepo->getStatPlayedGroupGames($group->id);
        $data = $this->statisticsGenerator->getAll($playedGames);
        return response()->json($data, 200);
    }

    public function groupYearStats(Group $group, $year)
    {
        $playedGames = $this->playedGameRepo->getStatPlayedGroupYearGames($group->id, $year);
        $data = $this->statisticsGenerator->getAll($playedGames);
        return response()->json($data, 200);
    }

    public function groupGameStats(Group $group, Game $game){
        $playedGames = $this->playedGameRepo->getStatPlayedGame($group->id, $game->id);
        $data = $this->statisticsGenerator->getAll($playedGames);
        return response()->json($data, 200);
    }
}
