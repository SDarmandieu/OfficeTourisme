<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\City;
use App\Game;
use App\Point;
use App\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class QuestionTest extends TestCase
{
    use DatabaseTransactions;

    protected $city;
    protected $user;
    protected $game;
    protected $point;

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
    }

    /**
     * testing question create when authenticated
     *
     * @return void
     */
    public function testQuestionCreateRoute()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/game/' . $this->game->id . '/point/' . $this->point->id . '/question/create');
        $response
            ->assertStatus(200)
            ->assertViewHas(['game', 'point']);

        $data = $response->original->getData();
        $this->assertInstanceOf('App\Point', $data['point']);
        $this->assertInstanceOf('App\Game', $data['game']);
    }

    /**
     * testing question store when authenticated
     *
     * @return void
     */
    public function testQuestionStoreRoute()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/game/' . $this->game->id . '/point/' . $this->point->id . '/question/create')
            ->post('/game/' . $this->game->id . '/point/' . $this->point->id . '/question/store', [
                'question' => 'foo',
                'file' => 1,
                'expe' => 16
            ]);

        $response
            ->assertRedirect('/game/' . $this->game->id . '/point/' . $this->point->id)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('questions', ['content' => 'foo']);
    }

    /**
     * testing question store with all fields invalid
     */
    public function testQuestionStoreRouteInvalidFields()
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
     * testing question destroy when authenticated
     *
     * @return void
     */
    public function testQuestionDestroyRoute()
    {
        $question = Question::create([
            'content' => 'foo',
            'file_id' => 1,
            'expe' => 16,
            'game_id' => $this->game->id,
            'point_id' => $this->point->id
        ]);

        $this->assertDatabaseHas('questions', ['content' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'DELETE',
                '/question/destroy/' . $question->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/game/' . $this->game->id . '/point/' . $this->point->id)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('questions', ['content' => 'foo']);
    }

    /**
     * testing question edit when authenticated
     *
     * @return void
     */
    public function testQuestionEditRoute()
    {
        $question = Question::create([
            'content' => 'foo',
            'file_id' => 1,
            'expe' => 16,
            'game_id' => $this->game->id,
            'point_id' => $this->point->id
        ]);

        $this->assertDatabaseHas('questions', ['content' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->get('/question/edit/' . $question->id);

        $response
            ->assertStatus(200)
            ->assertViewHas('question');

        $current = $response->original->getData()['question'];
        $this->assertInstanceOf('App\Question', $current);
    }

    /**
     * testing question update when authenticated
     *
     * @return void
     */
    public function testQuestionUpdateRoute()
    {
        $question = Question::create([
            'content' => 'foo',
            'file_id' => 1,
            'expe' => 16,
            'game_id' => $this->game->id,
            'point_id' => $this->point->id
        ]);

        $this->assertDatabaseHas('questions', ['content' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->from('/question/edit/' . $question->id)
            ->call(
                'PUT',
                '/question/update/' . $question->id,
                [
                    'question' => 'bar',
                    'file' => 2,
                    'expe' => '32',
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/game/' . $this->game->id . '/point/' . $this->point->id)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this
            ->assertDatabaseHas('questions', ['content' => 'bar'])
            ->assertDatabaseMissing('questions', ['content' => 'foo']);
    }

    /**
     * testing question update with all fields invalid
     */
    public function testQuestionUpdateRouteInvalidFields()
    {
        $question = Question::create([
            'content' => 'foo',
            'file_id' => 1,
            'expe' => 16,
            'game_id' => $this->game->id,
            'point_id' => $this->point->id
        ]);

        $this->assertDatabaseHas('questions', ['content' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->from('/question/edit/' . $question->id)
            ->call(
                'PUT',
                '/question/update/' . $question->id,
                [
                    'question' => null,
                    'file' => -2,
                    'expe' => 'toto',
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/question/edit/' . $question->id)
            ->assertStatus(302)
            ->assertSessionHasErrors(['question', 'file', 'expe']);

        $this
            ->assertDatabaseHas('questions', ['content' => 'foo'])
            ->assertDatabaseMissing('questions', ['expe' => 'toto']);
    }
}
