<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\City;
use App\Point;

class GamePointController extends Controller
{
    /**
     * Display a listing of the resource
     *
     * @param $city_id
     * @param $game_id
     * @param $point_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($city_id,$game_id,$point_id)
    {
        $game = Game::findOrFail($game_id);
        $city = City::findOrFail($city_id);
        $point = Point::findOrFail($point_id);

        return view('gamePoint.index',compact('game','city','point'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param $city_id
     * @param $game_id
     * @param $point_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function attach($city_id,$game_id,$point_id)
    {
        $game = Game::findOrFail($game_id);
        $city = City::findOrFail($city_id);

        $game->points()->attach($point_id);

        return view('game.home',compact('game','city'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $city_id
     * @param $game_id
     * @param $point_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detach($city_id,$game_id,$point_id)
    {
        $game = Game::findOrFail($game_id);
        $city = City::findOrFail($city_id);

        $game->points()->detach($point_id);

        return view('game.home',compact('game','city'));
    }

}
