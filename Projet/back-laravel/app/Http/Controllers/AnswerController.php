<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Http\Requests\StoreAnswer;
use App\Question;
use App\File;

class AnswerController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @param $question_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($question_id)
    {
        $question = Question::findOrFail($question_id);
        $images = File::where('city_id', '=', $question->game->city->id)
            ->whereHas('imagetype',
                function ($q) {
                    $q->where('title', '=', 'game');
                })->get();

        return view('answer.create', compact('images', 'question'));
    }

    /**
     * Store a newly created resource in storage.
     * @param $question_id
     * @param StoreAnswer $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($question_id, StoreAnswer $request)
    {
        $validated = $request->validated();
        $question = Question::find($question_id);
        Answer::create([
            'content' => $validated['answer'],
            'valid' => $validated['valid'],
            'question_id' => $question_id,
            'file_id' => $validated['file']
        ]);

        return redirect()->route('gamePointIndex', [$question->game->id, $question->point->id])->with('success', 'La réponse a bien été créée.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param $answer_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($answer_id)
    {
        $answer = Answer::findOrFail($answer_id);
        $images = File::where('city_id', '=', $answer->question->game->city->id)
            ->whereHas('imagetype',
                function ($q) {
                    $q->where('title', '=', 'game');
                })->get();
        return view('answer.edit', compact('images', 'answer'));
    }

    /**
     * Update the specified resource in storage.
     * @param $answer_id
     * @param StoreAnswer $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($answer_id, StoreAnswer $request)
    {
        $validated = $request->validated();
        $answer = Answer::findOrFail($answer_id);
        $answer->update([
            'content' => $validated['answer'],
            'valid' => $validated['valid'],
            'file_id' => $validated['file']
        ]);

        return redirect()->route('gamePointIndex', [$answer->question->game->id, $answer->question->point->id])->with('success', 'La réponse a bien été modifiée.');
    }

    /**
     * Remove the specified resource from storage.
     * @param $answer_id
     * @return \Illuminate\Http\RedirectResponse
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
