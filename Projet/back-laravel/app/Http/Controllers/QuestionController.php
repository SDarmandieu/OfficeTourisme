<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestion;
use App\Question;
use App\Point;
use App\Game;
use App\File;

class QuestionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     * @param $game_id
     * @param $point_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($game_id, $point_id)
    {
        $game = Game::findOrFail($game_id);
        $point = Point::findOrFail($point_id);
        $files = File::where('city_id', '=', $game->city_id)
            ->whereHas('imagetype',
                function ($q) {
                    $q->where('title', '=', 'game');
                })->get();

        return view('question.create', compact('game', 'point', 'files'));
    }

    /**
     * Store a newly created resource in storage.
     * @param $game_id
     * @param $point_id
     * @param StoreQuestion $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($game_id, $point_id, StoreQuestion $request)
    {
        $validated = $request->validated();
        Question::create([
            'content' => $validated['question'],
            'expe' => $validated['expe'],
            'point_id' => $point_id,
            'game_id' => $game_id,
            'file_id' => $validated['file']
        ]);

        return redirect()->route('gamePointIndex', [$game_id, $point_id])->with('success', 'La question a bien été créée.');
    }

    /**
     * Show the form for editing the specified resource.
     * @param $question_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($question_id)
    {
        $question = Question::findOrFail($question_id);
        $images = File::whereHas('imagetype',
            function ($q) {
                $q->where('title', '=', 'game');
            })
            ->where('city_id', '=', $question->game->city->id)
            ->get();
        return view('question.edit', compact('question', 'images'));
    }

    /**
     * Update the specified resource in storage.
     * @param $question_id
     * @param StoreQuestion $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($question_id, StoreQuestion $request)
    {
        $validated = $request->validated();
        $question = Question::findOrFail($question_id);
        $question->update([
            'content' => $validated['question'],
            'expe' => $validated['expe'],
            'file_id' => $validated['file']
        ]);

        return redirect()->route('gamePointIndex', [$question->game->id, $question->point->id])->with('success', 'La question a bien été modifiée');
    }

    /**
     * Remove the specified resource from storage.
     * @param $question_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($question_id)
    {
        $question = Question::findOrFail($question_id);
        $game_id = $question->game->id;
        $point_id = $question->point->id;
        $question->delete();
        return redirect()->route('gamePointIndex', [$game_id, $point_id])->with('success', 'La question a bien été supprimée.');
    }
}
