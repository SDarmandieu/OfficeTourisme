<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();
        return view('city.index',compact('cities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        City::create([
            'name'=>$request->input('name'),
            'lat'=>$request->input('latitude'),
            'lon'=>$request->input('longitude')
        ]);

        return redirect()->route('cityIndex')->with('success', 'La ville a bien été créée.');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $city = City::findOrFail($id);
        return view('city.edit',compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);
        $city->update([
            'name' => $request->input('name'),
            'lat' => $request->input('latitude'),
            'lon' => $request->input('longitude')
        ]);

        return redirect()->route('cityIndex')->with('success', 'La ville a bien été modifiée.');
    }

    /**
     *
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id)
    {
        City::find($id)->delete();
        return redirect()->route('cityIndex')->with('success', 'La ville a bien été supprimée.');

    }

    /**
     * Show the homepage of a specific city (point/game/image)
     */

    public function home($id)
    {
        $city = City::findOrFail($id);
        return view('city.home',compact('city'));
    }
}
