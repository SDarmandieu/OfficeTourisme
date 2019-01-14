<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Game;
use App\City;
use App\Point;
use App\Question;

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
    public function index($game_id,$point_id)
    {
        $game = Game::findOrFail($game_id);
        $point = Point::findOrFail($point_id);

        $question = Question::where('game_id','=',$game_id)->where('point_id','=',$point_id)->get()->first();

        return view('gamePoint.index',compact('game','point','question'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param $city_id
     * @param $game_id
     * @param $point_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function attach($game_id,$point_id)
    {
        $game = Game::findOrFail($game_id);
        $game->points()->attach($point_id);

        return redirect()->route('gameHome',$game)->with('success', 'Le point a bien été ajouté au jeu de piste');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $city_id
     * @param $game_id
     * @param $point_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detach($game_id,$point_id)
    {
        $game = Game::findOrFail($game_id);

        $game->points()->detach($point_id);

        return redirect()->route('gameHome',$game)->with('success', 'Le point a bien été retiré du jeu de piste');
    }

}
