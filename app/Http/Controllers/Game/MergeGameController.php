<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Services\GameService\MergeGameService;
use Illuminate\Container\Container;

class MergeGameController extends Controller
{
    public function update(Game $game, Game $merge_game)
    {
        $container = Container::getInstance();
        $mergeService = $container->make(MergeGameService::class);
        return response()->json($mergeService->mergeGame($game, $merge_game), 201);
    }
}
