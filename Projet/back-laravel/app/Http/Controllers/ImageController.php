<?php

namespace App\Http\Controllers;

use App\Image;
use App\City;
use App\Imagetype;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($city_id)
    {
        $city = City::find($city_id);
        $imagetypes = Imagetype::all();
        $images = Image::where('city_id', $city_id)->get();
        return view('image.index', compact('images', 'city','imagetypes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($city_id)
    {
        $city = City::find($city_id);
        $imagetypes = Imagetype::all();
        return view('image.create', compact('city', 'imagetypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($city_id, Request $request)
    {
        $file = $request->file;
        $filename = $file->getClientOriginalName();

        $path = Storage::disk('public')->putFile('images', $file, 'public');

        Image::create([
            'filename' => $filename,
            'path' => $path,
            'alt' => $request->input('alt'),
            'city_id' => $city_id,
            'imagetype_id' => $request->input('type')
        ]);

        return redirect()->route('imageIndex', $city_id)->with('success', 'L\'image a bien été créée.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Image $image
     * @return \Illuminate\Http\Response
     */
    public function edit($image_id)
    {
        $image = Image::findOrFail($image_id);
        $imagetypes = Imagetype::all();
        return view('image.edit', compact('image', 'imagetypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Image $image
     * @return \Illuminate\Http\Response
     */
    public function update($image_id, Request $request)
    {
        $image = Image::findOrFail($image_id);
        $image->update([
            'alt' => $request->input('alt'),
            'imagetype_id' => $request->input('type')
        ]);

        return redirect()->route('imageIndex', $image->city->id)->with('success', 'L\'image a bien été modifiée.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Image $image
     * @return \Illuminate\Http\Response
     */
    public function destroy($image_id)
    {
        $image = Image::find($image_id);
        $city_id = $image->city->id;
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return redirect()->route('imageIndex', $city_id)->with('success', 'L\'image a bien été supprimée.');
    }
}
