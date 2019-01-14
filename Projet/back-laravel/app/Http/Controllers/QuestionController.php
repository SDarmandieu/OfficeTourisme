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
    public function create($game_id, $point_id)
    {
        $game = Game::findOrFail($game_id);
        $point = Point::findOrFail($point_id);
        $images = Image::where('city_id', '=', $game->city->id)
            ->whereHas('imagetype',
                function ($q) {
                    $q->where('title', '=', 'game');
                })->get();

        return view('question.create', compact( 'game', 'point', 'images'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($game_id, $point_id, Request $request)
    {
        Question::create([
            'content' => $request->input('question'),
            'expe' => $request->input('expe'),
            'point_id' => $point_id,
            'game_id' => $game_id,
            'image_id' => $request->input('image')
        ]);

        return redirect()->route('gamePointIndex', [$game_id, $point_id])->with('success', 'La question a bien été créée.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function edit($question_id)
    {
        $question = Question::findOrFail($question_id);
        $images = Image::whereHas('imagetype',
            function ($q) {
                $q->where('title', '=', 'game');
            })
            ->where('city_id','=',$question->game->city->id)
            ->get();
        return view('question.edit', compact('question','images'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function update($question_id, Request $request)
    {
        $question = Question::findOrFail($question_id);
        $question->update([
            'content' => $request->input('question'),
            'expe' => $request->input('expe'),
            'image_id' => $request->input('image')
        ]);

        return redirect()->route('gamePointIndex', [$question->game->id,$question->point->id])->with('success', 'La question a bien été modifiée');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($question_id)
    {
        $question = Question::findOrFail($question_id);
        $game_id = $question->game->id;
        $point_id = $question->point->id;
        $question->delete();
        return redirect()->route('gamePointIndex', [$game_id,$point_id])->with('success', 'La question a bien été supprimée.');

    }
}
