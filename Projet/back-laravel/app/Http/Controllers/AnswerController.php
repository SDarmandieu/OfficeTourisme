<?php

namespace App\Http\Controllers;

use App\Answer;
use App\City;
use App\Game;
use App\Point;
use App\Question;
use App\Image;
use Illuminate\Http\Request;

class AnswerController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($city_id, $game_id, $point_id,$question_id)
    {

        $city = City::findOrFail($city_id);
        $game = Game::findOrFail($game_id);
        $point = Point::findOrFail($point_id);
        $question = Question::findOrFail($question_id);
        $images = Image::where('city_id', '=', $city_id)
            ->whereHas('imagetype',
                function ($q) {
                    $q->where('title', '=', 'game');
                })->get();

        return view('answer.create', compact('city', 'game', 'point', 'images','question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($city_id,$game_id,$point_id,$question_id,Request $request)
    {
        Answer::create([
            'content' => $request->input('answer'),
            'valid' => (bool)$request->input('valid'),
            'question_id' => $question_id,
            'image_id' => $request->input('image')
        ]);

//        $question->images()->attach($request->input('image'));

        return redirect()->route('gamePointIndex',[$city_id,$game_id,$point_id])->with('success', 'La réponse a bien été créée.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit($city_id, $game_id,$point_id,$question_id,$answer_id)
    {
        $images = Image::whereHas('imagetype',
            function ($q) {
                $q->where('title', '=', 'game');
            })->get();
        $city = City::findOrFail($city_id);
        $game = Game::findOrFail($game_id);
        $point = Point::findOrFail($point_id);
        $question = Question::findOrFail($question_id);
        $answer = Answer::findOrFail($answer_id);
        return view('answer.edit', compact('city', 'game', 'images','point','question','answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update($city_id,$game_id,$point_id,$question_id,$answer_id,Request $request)
    {
        $answer = Answer::findOrFail($answer_id);
        $answer->update([
            'content' => $request->input('answer'),
            'valid' => (bool)$request->input('valid'),
            'image_id' => $request->input('image')
        ]);

        return redirect()->route('gamePointIndex',[$city_id,$game_id,$point_id])->with('success', 'La réponse a bien été modifiée.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy($city_id, $game_id, $point_id, $question_id,$answer_id)
    {
        Answer::findOrFail($answer_id)->delete();
        return redirect()->route('gamePointIndex', [$city_id, $game_id, $point_id])->with('success', 'La réponse a bien été supprimée.');

    }
}
