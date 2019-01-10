<?php

namespace App\Http\Controllers;

use App\Game;
use App\City;
use App\Image;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($city_id)
    {
        $city = City::find($city_id);
        $games = Game::where('city_id', $city_id)->get();
        return view('game.index',compact('city','games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($city_id)
    {
        $icons = Image::whereHas('imagetype',
            function($q)
            {
                $q->where('title','=','icon');
            })->get();

        $city = City::find($city_id);
        return view('game.create', compact('city','icons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($city_id, Request $request)
    {
        Game::create([
            'desc' => $request->input('desc'),
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'image_id' => $request->input('icon'),
            'city_id' => $city_id
        ]);
        return redirect()->route('gameIndex', $city_id)->with('success', 'Le jeu de piste a bien été crée.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit($city_id, $game_id)
    {
        $icons = Image::whereHas('imagetype',
            function($q)
            {
                $q->where('title','=','icon');
            })->get();
        $city = City::findOrFail($city_id);
        $game = Game::findOrFail($game_id);
        return view('game.edit', compact('city','game','icons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update($city_id,$game_id,Request $request)
    {
        $game = Game::findOrFail($game_id);
        $game->update([
            'desc' => $request->input('desc'),
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'image_id' => $request->input('icon')
        ]);

        return redirect()->route('gameIndex',$city_id)->with('success', 'Le jeu de piste a bien été modifié.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy($city_id,$game_id)
    {
        Game::find($game_id)->delete();
        return redirect()->route('gameIndex',$city_id)->with('success', 'Le jeu de piste a bien été supprimé.');

    }

    public function home($city_id,$game_id)
    {
        $city = City::find($city_id);
        $game = Game::find($game_id);
        return view('game.home',compact('game','city'));
    }
}
