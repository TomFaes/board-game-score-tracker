<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Group;
use App\Models\GroupGame;
use App\Models\GroupGameLink;
use App\Repositories\GroupGameLinkRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin:Admin, View',['only' => ['view']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * for testing only
     */
    public function view()
     {

        if(Auth::user()->id != 1){
            return "No acces to this page";
        }
         echo Auth::user()->id."<hr>";
         echo "Dit is een test pagina voor admins<hr>";

         $groupgameLink = new GroupGameLinkRepo();
         dd($groupgameLink->getLinksOfGroupGame(1)->toArray());

         $gameLink = GroupGameLink::with(['groupGame'])->get();
         dd($gameLink->toArray());



         //$games = GroupGame::leftJoin('games', 'group_games.game_id', '!=', 'games.id')->with('game')->get();

        /*
        echo"<hr>";
        echo "game: ".$games->name;
        echo "<br>group id: ".$games->groups[0]->group_id;
*/

         echo"<hr>";
/*
         echo "<pre>";
         print_r($games->toArray());
         echo "</pre>";
         */
         dd($games);



         /*
         $repo = new \App\Repositories\GroupGameRepo();
         dd($repo->getGroupGame(1));
*/



     }


}
