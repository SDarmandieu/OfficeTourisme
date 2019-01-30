<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('game_point')->get()->each(function ($game_point) {
            $question = factory(App\Question::class)->make();
            $question->game_id = $game_point->game_id;
            $question->point_id = $game_point->point_id;
            $answers = factory(App\Answer::class,4)->make();
            foreach($answers as $answer)
            {
                $answer->question_id = $question->id;
            }
            $question->save();
            $question->answers()->saveMany($answers);
        });
    }
}
