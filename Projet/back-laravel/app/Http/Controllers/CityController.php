<?php

namespace App\Http\Controllers;

use App\City;
use App\File;
use App\Http\Requests\StoreCity;
use Illuminate\Support\Facades\Storage;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $cities = City::all();
        return view('city.index', compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreCity $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCity $request)
    {
        $validated = $request->validated();
        City::create([
            'name' => $validated['name'],
            'lat' => $validated['latitude'],
            'lon' => $validated['longitude']
        ]);

        return redirect()->route('cityIndex')->with('success', 'La ville a bien été créée.');

    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $city = City::findOrFail($id);
        return view('city.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     * @param StoreCity $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreCity $request, $id)
    {
        $validated = $request->validated();
        $city = City::findOrFail($id);
        $city->update([
            'name' => $validated['name'],
            'lat' => $validated['latitude'],
            'lon' => $validated['longitude']
        ]);

        return redirect()->route('cityIndex')->with('success', 'La ville a bien été modifiée.');
    }

    /**
     * Remove the specified resource from storage.
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $paths = File::where('city_id', '=', $id)->pluck('path')->toArray();
        Storage::disk('public')->delete($paths);
        City::find($id)->delete();
        return redirect()->route('cityIndex')->with('success', 'La ville a bien été supprimée.');
    }

    /**
     * Show the homepage of a specific city (point/game/image)
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home($id)
    {
        $city = City::findOrFail($id);
        return view('city.home', compact('city'));
    }
}
