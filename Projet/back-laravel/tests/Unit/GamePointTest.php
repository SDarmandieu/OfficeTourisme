<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\City;
use App\Game;
use App\Point;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GamePointTest extends TestCase
{
    use DatabaseTransactions;

    protected $city;
    protected $game;
    protected $point;
    protected $user;

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
     * testing game point attach when authenticated
     */
    public function testGamePointAttach()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/game/' . $this->game->id)
            ->post('/game/' . $this->game->id . '/point/attach/' . $this->point->id);

        $response
            ->assertRedirect('/game/' . $this->game->id)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('game_point', ['game_id' => $this->game->id, 'point_id' => $this->point->id]);
    }

    /**
     * testing game point detach when authenticated
     */
    public function testGamePointDetach()
    {
        DB::table('game_point')->insert([
            'game_id' => $this->game->id,
            'point_id' => $this->point->id
        ]);

        $response = $this
            ->actingAs($this->user)
            ->from('/game/' . $this->game->id)
            ->call(
                'DELETE',
                '/game/' . $this->game->id . '/point/detach/' . $this->point->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/game/' . $this->game->id)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('game_point', ['game_id' => $this->game->id, 'point_id' => $this->point->id]);
    }

    /**
     * testing game point index route when authenticated
     */
    public function testGamePointIndexRoute()
    {
        DB::table('game_point')->insert([
            'game_id' => $this->game->id,
            'point_id' => $this->point->id
        ]);

        $response = $this
            ->actingAs($this->user)
            ->from('/game/' . $this->game->id)
            ->get('/game/' . $this->game->id . '/point/' . $this->point->id);

        $response
            ->assertStatus(200)
            ->assertViewHas(['point', 'game']);

        $dataPoint = $response->original->getData()['point'];
        $dataGame = $response->original->getData()['game'];
        $this->assertInstanceOf('App\Point', $dataPoint);
        $this->assertInstanceOf('App\Game', $dataGame);
    }
}
