<?php

namespace App\Http\Controllers;

use App\Game;
use App\City;
use App\File;
use Illuminate\Http\Request;

class GameController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param $city_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($city_id)
    {
        $city = City::find($city_id);
        $games = Game::where('city_id', $city_id)->get();
        return view('game.index', compact('city', 'games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $city_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($city_id)
    {
        $icons = File::whereHas('imagetype',
            function ($q) {
                $q->where('title', '=', 'icon');
            })->get();

        $city = City::find($city_id);
        return view('game.create', compact('city', 'icons'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $city_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($city_id, Request $request)
    {
        $game = Game::create([
            'desc' => $request->input('desc'),
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'city_id' => $city_id
        ]);

        $game->files()->attach($request->input('icon'));

        return redirect()->route('gameIndex', $city_id)->with('success', 'Le jeu de piste a bien été créé.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $city_id
     * @param $game_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($game_id)
    {
        $icons = File::whereHas('imagetype',
            function ($q) {
                $q->where('title', '=', 'icon');
            })->get();
        $game = Game::findOrFail($game_id);
        return view('game.edit', compact('game', 'icons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $city_id
     * @param $game_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($game_id, Request $request)
    {
        $game = Game::findOrFail($game_id);
        $game->update([
            'desc' => $request->input('desc'),
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'image_id' => $request->input('icon')
        ]);

        return redirect()->route('gameIndex', $game->city->id)->with('success', 'Le jeu de piste a bien été modifié.');
    }

    /**
     * Remove the specified resource from storage.
     * @param $city_id
     * @param $game_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($game_id)
    {
        $game = Game::find($game_id);
        $city_id = $game->city->id;
        $game->delete();
        return redirect()->route('gameIndex', $city_id)->with('success', 'Le jeu de piste a bien été supprimé.');

    }

    /**
     * Display game home view
     *
     * @param $city_id
     * @param $game_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home($game_id)
    {
        $game = Game::find($game_id);
        $points = $game->points()->paginate(5);
        return view('game.home', compact('game', 'points'));
    }
}
