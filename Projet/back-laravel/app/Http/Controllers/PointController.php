<?php

namespace App\Http\Controllers;

use App\Point;
use App\City;
use Illuminate\Http\Request;

class PointController extends Controller
{
    /**
     * Display a listing of point of choosen city
     *
     * @return \Illuminate\Http\Response
     */
    public function index($city_id)
    {
        $city = City::find($city_id);
        $points = Point::where('city_id', $city_id)->get();
        return view('point.index', compact('points', 'city'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($city_id)
    {
        $city = City::find($city_id);
        return view('point.create', compact('city'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($city_id, Request $request)
    {
        Point::create([
            'desc' => $request->input('desc'),
            'lat' => $request->input('latitude'),
            'lon' => $request->input('longitude'),
            'city_id' => $city_id
        ]);

        return redirect()->route('pointIndex', $city_id)->with('success', 'Le point d\'interêt a bien été crée.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Point $point
     * @return \Illuminate\Http\Response
     */
    public function edit($city_id, $point_id)
    {
        $city = City::findOrFail($city_id);
        $point = Point::findOrFail($point_id);
        return view('point.edit', compact('city','point'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Point $point
     * @return \Illuminate\Http\Response
     */
    public function update($city_id,$point_id,Request $request)
    {
        $point = Point::findOrFail($point_id);
        $point->update([
            'desc' => $request->input('desc'),
            'lat' => $request->input('latitude'),
            'lon' => $request->input('longitude')
        ]);

        return redirect()->route('pointIndex',$city_id)->with('success', 'Le point d\'interêt a bien été modifié.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Point $point
     * @return \Illuminate\Http\Response
     */
    public function destroy($city_id,$point_id)
    {
        Point::find($point_id)->delete();
        return redirect()->route('pointIndex',$city_id)->with('success', 'Le point d\'interêt a bien été supprimé.');

    }
}
