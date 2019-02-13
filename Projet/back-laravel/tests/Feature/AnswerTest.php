<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\City;
use App\Game;
use App\Point;
use App\Question;
use App\Answer;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AnswerTest extends TestCase
{
    use DatabaseTransactions;

    protected $city;
    protected $user;
    protected $game;
    protected $point;
    protected $question;

    /**
     * Set the URL of the previous request.
     *
     * @param  string $url
     * @return $this
     */
    public function from($url)
    {
        $this
            ->app['session']
            ->setPreviousUrl($url);

        return $this;
    }

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->city = City::create([
            'name' => 'fooCity',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);
        $this->game = Game::create([
            'name' => 'fooGame',
            'age' => '7/9 ans',
            'desc' => 'Lorem ipsum',
            'city_id' => $this->city->id
        ]);
        $this->point = Point::create([
            'desc' => 'fooPoint',
            'lat' => '68.000',
            'lon' => '24.000',
            'city_id' => $this->city->id
        ]);
        $this->question = Question::create([
            'content' => 'foo',
            'file_id' => 1,
            'expe' => 16,
            'game_id' => $this->game->id,
            'point_id' => $this->point->id
        ]);
    }

    /**
     * testing answer create when authenticated
     *
     * @return void
     */
    public function testAnswerCreateRoute()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/question/' . $this->question->id . '/answer/create');
        $response
            ->assertStatus(200)
            ->assertViewHas(['question']);

        $data = $response->original->getData();
        $this->assertInstanceOf('App\Question', $data['question']);
    }

    /**
     * testing answer store when authenticated
     *
     * @return void
     */
    public function testAnswerStoreRoute()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/question/' . $this->question->id . '/answer/create')
            ->post('/question/' . $this->question->id . '/answer/store',
                [
                    'answer' => 'foo',
                    'valid' => 1,
                    'file' => 1
                ]);

        $response
            ->assertRedirect('/game/' . $this->game->id . '/point/' . $this->point->id)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('answers', ['content' => 'foo']);
    }

    /**
     * testing answer store with all fields invalid
     */
    public function testAnswerStoreRouteInvalidFields()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/game/' . $this->game->id . '/point/' . $this->point->id . '/question/create')
            ->post('/game/' . $this->game->id . '/point/' . $this->point->id . '/question/store', [
                'question' => null,
                'file' => -1,
                'expe' => 1000
            ]);

        $response
            ->assertRedirect('/game/' . $this->game->id . '/point/' . $this->point->id . '/question/create')
            ->assertStatus(302)
            ->assertSessionHasErrors(['question', 'file', 'expe']);

        $this->assertDatabaseMissing('questions', ['expe' => 1000]);
    }

    /**
     * testing answer destroy when authenticated
     *
     * @return void
     */
    public function testAnswerDestroyRoute()
    {
        $answer = Answer::create([
            'content' => 'foo',
            'file_id' => 1,
            'valid' => 1,
            'question_id' => $this->question->id
        ]);

        $this->assertDatabaseHas('answers', ['content' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'DELETE',
                '/answer/destroy/' . $answer->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/game/' . $this->game->id . '/point/' . $this->point->id)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('answers', ['content' => 'foo']);
    }

    /**
     * testing answer edit when authenticated
     *
     * @return void
     */
    public function testAnswerEditRoute()
    {
        $answer = Answer::create([
            'content' => 'foo',
            'file_id' => 1,
            'valid' => 1,
            'question_id' => $this->question->id
        ]);

        $this->assertDatabaseHas('answers', ['content' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->get('/answer/edit/' . $answer->id);

        $response
            ->assertStatus(200)
            ->assertViewHas('answer');

        $current = $response->original->getData()['answer'];
        $this->assertInstanceOf('App\Answer', $current);
    }

    /**
     * testing answer update when authenticated
     *
     * @return void
     */
    public function testAnswerUpdateRoute()
    {
        $answer = Answer::create([
            'content' => 'foo',
            'file_id' => 1,
            'valid' => 1,
            'question_id' => $this->question->id
        ]);

        $this->assertDatabaseHas('answers', ['content' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->from('/answer/edit/' . $answer->id)
            ->call(
                'PUT',
                '/answer/update/' . $answer->id,
                [
                    'answer' => 'bar',
                    'file' => 2,
                    'valid' => 0,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/game/' . $this->game->id . '/point/' . $this->point->id)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this
            ->assertDatabaseHas('answers', ['content' => 'bar'])
            ->assertDatabaseMissing('answers', ['content' => 'foo']);
    }

    /**
     * testing answer update with all fields invalid
     */
    public function testAnswerUpdateRouteInvalidFields()
    {
        $answer = Answer::create([
            'content' => 'foo',
            'file_id' => 1,
            'valid' => 1,
            'question_id' => $this->question->id
        ]);

        $this->assertDatabaseHas('answers', ['content' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->from('/answer/edit/' . $answer->id)
            ->call(
                'PUT',
                '/answer/update/' . $answer->id,
                [
                    'answer' => null,
                    'file' => -2,
                    'valid' => 2,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/answer/edit/' . $answer->id)
            ->assertStatus(302)
            ->assertSessionHasErrors(['answer', 'file', 'valid']);

        $this
            ->assertDatabaseHas('answers', ['content' => 'foo'])
            ->assertDatabaseMissing('answers', ['valid' => 2]);
    }
}
