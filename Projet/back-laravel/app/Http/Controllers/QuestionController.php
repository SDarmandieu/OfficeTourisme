<?php

namespace App\Http\Controllers;

use App\Question;
use App\Point;
use App\Game;
use App\City;
use App\Image;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($city_id, $game_id, $point_id)
    {

        $city = City::findOrFail($city_id);
        $game = Game::findOrFail($game_id);
        $point = Point::findOrFail($point_id);
        $images = Image::where('city_id', '=', $city_id)
            ->whereHas('imagetype',
                function ($q) {
                    $q->where('title', '=', 'game');
                })->get();

        return view('question.create', compact('city', 'game', 'point', 'images'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($city_id, $game_id, $point_id, Request $request)
    {
        $question = Question::create([
            'content' => $request->input('question'),
            'expe' => $request->input('expe'),
            'point_id' => $point_id,
            'game_id' => $game_id,
            'image_id' => $request->input('image')
        ]);

//        $question->images()->attach($request->input('image'));

        return redirect()->route('gamePointIndex', [$city_id, $game_id, $point_id])->with('success', 'La question a bien été créée.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function edit($city_id, $game_id,$point_id,$question_id)
    {
        $images = Image::whereHas('imagetype',
            function ($q) {
                $q->where('title', '=', 'game');
            })->get();
        $city = City::findOrFail($city_id);
        $game = Game::findOrFail($game_id);
        $point = Point::findOrFail($point_id);
        $question = Question::findOrFail($question_id);
        return view('question.edit', compact('city', 'game', 'images','point','question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function update($city_id, $game_id,$point_id,$question_id, Request $request)
    {
        $question = Question::findOrFail($question_id);
        $question->update([
            'content' => $request->input('question'),
            'expe' => $request->input('expe'),
            'image_id' => $request->input('image')
        ]);

        return redirect()->route('gamePointIndex', [$city_id,$game_id,$point_id,$question_id])->with('success', 'La question a bien été modifiée');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($city_id, $game_id, $point_id, $question_id)
    {
        Question::findOrFail($question_id)->delete();
        return redirect()->route('gamePointIndex', [$city_id, $game_id, $point_id])->with('success', 'La question a bien été supprimée.');

    }
}
