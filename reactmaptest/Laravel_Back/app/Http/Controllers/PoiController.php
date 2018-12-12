<?php

namespace App\Http\Controllers;

use App\Poi;
use Illuminate\Http\Request;

class PoiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = Poi::all();
        return response()->json([
            'pois' => $res
        ]);
    }

    public function testData(Request $request)
    {
        $res= $request->all();
        return response()->json([
            'res'=>$res
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Poi  $poi
     * @return \Illuminate\Http\Response
     */
    public function show(Poi $poi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Poi  $poi
     * @return \Illuminate\Http\Response
     */
    public function edit(Poi $poi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Poi  $poi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Poi $poi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Poi  $poi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Poi $poi)
    {
        //
    }
}
