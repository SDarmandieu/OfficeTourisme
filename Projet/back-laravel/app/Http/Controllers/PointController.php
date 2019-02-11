<?php

namespace App\Http\Controllers;

use App\Point;
use App\City;
use App\Http\Requests\StorePoint;

class PointController extends Controller
{
    /**
     * Display a listing of point of choosen city
     * @param $city_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($city_id)
    {
        $city = City::find($city_id);
        $points = Point::where('city_id', $city_id)->get();
        return view('point.index', compact('points', 'city'));
    }

    /**
     * Show the form for creating a new resource.
     * @param $city_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($city_id)
    {
        $city = City::find($city_id);
        return view('point.create', compact('city'));
    }

    /**
     * Store a newly created resource in storage.
     * @param $city_id
     * @param StorePoint $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($city_id, StorePoint $request)
    {
        $validated = $request->validated();
        Point::create([
            'desc' => $validated['desc'],
            'lat' => $validated['latitude'],
            'lon' => $validated['longitude'],
            'city_id' => $city_id
        ]);

        return redirect()->route('pointIndex', $city_id)->with('success', 'Le point d\'interêt a bien été créé.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param $point_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($point_id)
    {
        $point = Point::findOrFail($point_id);
        return view('point.edit', compact('point'));
    }

    /**
     * Update the specified resource in storage.
     * @param $point_id
     * @param StorePoint $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($point_id, StorePoint $request)
    {
        $validated = $request->validated();
        $point = Point::findOrFail($point_id);
        $point->update([
            'desc' => $validated['desc'],
            'lat' => $validated['latitude'],
            'lon' => $validated['longitude'],
        ]);
        return redirect()->route('pointIndex', $point->city->id)->with('success', 'Le point d\'interêt a bien été modifié.');
    }

    /**
     * Remove the specified resource from storage.
     * @param $point_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($point_id)
    {
        $point = Point::find($point_id);
        $city_id = $point->city->id;
        $point->delete();
        return redirect()->route('pointIndex', $city_id)->with('success', 'Le point d\'interêt a bien été supprimé.');

    }
}
