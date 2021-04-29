<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Group;
use App\Models\GroupGame;
use App\Models\GroupGameLink;
use App\Repositories\GroupGameLinkRepo;
use App\Repositories\UserRepo;
use App\Repositories\GroupGameRepo;
use App\Repositories\PlayedGameRepo;
use App\Repositories\PlayedGameScoreRepo;
use App\Services\GameService\MergeGameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Services\StatisticsService\StatisticsFactory;
use Illuminate\Container\Container;

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
        $this->middleware('admin:Admin, View',['only' => ['view', 'view01']]);
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

    public function view01()
    {
        return view('view01');
    }

    /**
     * for testing only
     */
    public function view()
     {
        if(Auth::user()->id != 1){
            return "No acces to this page";
        }
        $data = array();
        echo "<pre>";

        echo "</pre>";


        return response()->json($data, 200);

     }


}
