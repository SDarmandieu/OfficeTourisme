<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\File;
use Illuminate\Http\Request;

class AnswerController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($question_id)
    {
        $question = Question::findOrFail($question_id);
        $images = File::where('city_id', '=', $question->game->city->id)
            ->whereHas('imagetype',
                function ($q) {
                    $q->where('title', '=', 'game');
                })->get();

        return view('answer.create', compact( 'images','question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($question_id,Request $request)
    {
        $question = Question::find($question_id);
        Answer::create([
            'content' => $request->input('answer'),
            'valid' => $request->input('valid'),
            'question_id' => $question_id,
            'file_id' => $request->input('file')
        ]);

        return redirect()->route('gamePointIndex',[$question->game->id,$question->point->id])->with('success', 'La réponse a bien été créée.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit($answer_id)
    {
        $answer = Answer::findOrFail($answer_id);
        $images = File::where('city_id', '=', $answer->question->game->city->id)
        ->whereHas('imagetype',
            function ($q) {
                $q->where('title', '=', 'game');
            })->get();
        return view('answer.edit', compact('images','answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update($answer_id,Request $request)
    {
        $answer = Answer::findOrFail($answer_id);
        $answer->update([
            'content' => $request->input('answer'),
            'valid' => (bool)($request->input('valid')),
            'file_id' => $request->input('file')
        ]);

        return redirect()->route('gamePointIndex',[$answer->question->game->id,$answer->question->point->id])->with('success', 'La réponse a bien été modifiée.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy($answer_id)
    {
        $answer = Answer::findOrFail($answer_id);
        $game_id = $answer->question->game->id;
        $point_id = $answer->question->point->id;
        $answer->delete();
        return redirect()->route('gamePointIndex', [$game_id, $point_id])->with('success', 'La réponse a bien été supprimée.');

    }
}
