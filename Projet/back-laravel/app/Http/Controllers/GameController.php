<?php

namespace App\Http\Controllers;

use App\Game;
use App\City;
use App\File;
use App\Http\Requests\StoreGame;

class GameController extends Controller
{

    /**
     * Display a listing of the resource.
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
     * @param $city_id
     * @param StoreGame $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($city_id, StoreGame $request)
    {
        $validated = $request->validated();
        $game = Game::create([
            'desc' => $validated['desc'],
            'name' => $validated['name'],
            'age' => $validated['age'],
            'published' => false,
            'city_id' => $city_id
        ]);

        $game->files()->attach($validated['icon']);

        return redirect()->route('gameIndex', $city_id)->with('success', 'Le jeu de piste a bien été créé.');
    }

    /**
     * Show the form for editing the specified resource.
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
        $current_icon =$game->files->first(function($f){return $f['imagetype_id']===2;});
        return view('game.edit', compact('game', 'icons', 'current_icon'));
    }

    /**
     * Update the specified resource in storage.
     * @param $game_id
     * @param StoreGame $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($game_id, StoreGame $request)
    {
        $validated = $request->validated();
        $game = Game::findOrFail($game_id);
        $game->update([
            'desc' => $validated['desc'],
            'name' => $validated['name'],
            'age' => $validated['age']
        ]);
        $game->files()->sync($validated['icon']);

        return redirect()->route('gameIndex', $game->city->id)->with('success', 'Le jeu de piste a bien été modifié.');
    }

    /**
     * Remove the specified resource from storage.
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
     * @param $game_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home($game_id)
    {
        $game = Game::find($game_id);
        $points = $game->points()->paginate(5);
        return view('game.home', compact('game', 'points'));
    }

    public function handlePublish($game_id)
    {
        $game = Game::find($game_id);
        $game->update([
            'published' => !$game->published,
        ]);
        return redirect()->route('gameIndex', $game->city->id)->with('success', 'Le status du jeu a bien été changé.');
    }
}
