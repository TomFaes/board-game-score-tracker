<?php

namespace App\Services\GameService;

use App\Models\Game;
use App\Repositories\Contracts\IGame;
use App\Repositories\Contracts\IGroupGame;
use App\Repositories\Contracts\IPlayedGame;

class MergeGameService
{
    protected $game;
    protected $groupGame;
    protected $playedGame;

    protected $gameId;
    protected $mergeId;

    public function __construct(IGame $game, IGroupGame $groupGame, IPlayedGame $playedGame)
    {
        $this->game = $game;
        $this->groupGame = $groupGame;
        $this->playedGame = $playedGame;

        $this->gameId = 0;
        $this->mergeId = 0;
    }

    /**
     * this function will merge 2 games together
     */
    public function mergeGame(Game $game, Game $toBeMergedGame)
    {
        $this->gameId = $game->id;
        $this->mergeId = $toBeMergedGame->id;

        $this->updateBaseGames();
        $this->updateExpansionPlayedGames();
        $this->updateGroupGames();
        $this->updatePlayedGame();
        //delete the game
        $this->game->delete($game);

        return true;
    }

    protected function updateBaseGames()
    {
        $this->game->updateBaseGameId($this->gameId ,$this->mergeId);
    }

    protected function updateExpansionPlayedGames()
    {
        $this->game->updateExpansion($this->gameId ,$this->mergeId);
    }

    protected function updateGroupGames()
    {
        $this->groupGame->updateGroupGameIds($this->gameId ,$this->mergeId);
    }

    protected function updatePlayedGame()
    {
        $this->playedGame->updateGameIds($this->gameId ,$this->mergeId);
    }
}
