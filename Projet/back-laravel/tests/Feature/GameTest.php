<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\City;
use App\Game;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GameTest extends TestCase
{
    use DatabaseTransactions;

    protected $city;
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
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);
    }

    /**
     * testing game index route when authenticated
     * @return void
     */
    public function testGameIndexRouteAuth()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/city/' . $this->city->id . '/game');

        $response
            ->assertStatus(200)
            ->assertViewHas('city');

        $current = $response->original->getData()['city'];
        $this->assertInstanceOf('App\City', $current);
    }

    /**
     * testing game index route when not authenticated
     * @return void
     */
    public function testGameIndexRouteNoAuth()
    {
        $response = $this->get('/city/' . $this->city->id . '/game');

        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * testing game create when authenticated
     *
     * @return void
     */
    public function testGameCreateRouteAuth()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/city/' . $this->city->id . '/game/create');
        $response
            ->assertStatus(200)
            ->assertViewHas('city');
    }

    /**
     * testing game create when not authenticated
     *
     * @return void
     */
    public function testGameCreateRouteNoAuth()
    {
        $response = $this->get('/city/' . $this->city->id . '/game/create');
        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * testing game store when authenticated
     *
     * @return void
     */
    public function testGameStoreRouteAuth()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/city/' . $this->city->id . '/game/create')
            ->post('/city/' . $this->city->id . '/game/store', [
                'name' => 'foo',
                'desc' => 'bar',
                'age' => '11/13 ans',
                'icon' => null
            ]);

        $response
            ->assertRedirect('/city/' . $this->city->id . '/game')
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('games', ['name' => 'foo']);
    }

    /**
     * testing game store with all fields invalid
     */
    public function testGameStoreRouteInvalidFields()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/city/' . $this->city->id . '/game/create')
            ->post('/city/' . $this->city->id . '/game/store', [
                'name' => null,
                'desc' => null,
                'age' => 'foo',
                'icon' => -1
            ]);

        $response
            ->assertRedirect('/city/' . $this->city->id . '/game/create')
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'desc', 'age', 'icon']);


        $this->assertDatabaseMissing('games', ['name' => null]);
    }

    /**
     * testing game store when not authenticated
     *
     * @return void
     */
    public function testGameStoreRouteNoAuth()
    {
        $response = $this
            ->from('/city/' . $this->city->id . '/game')
            ->post('/city/' . $this->city->id . '/game/store', [
                'name' => 'foo',
                'desc' => 'bar',
                'age' => '11/13 ans',
                'icon' => null
            ]);

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this->assertDatabaseMissing('games', ['name' => 'foo']);
    }

    /**
     * testing game destroy when authenticated
     *
     * @return void
     */
    public function testGameDestroyRouteAuth()
    {
        $game = Game::create([
            'name' => 'foo',
            'desc' => 'bar',
            'age' => '11/13 ans',
            'icon' => null,
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('games', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'DELETE',
                '/game/destroy/' . $game->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city/' . $this->city->id . '/game')
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('games', ['name' => 'foo']);
    }

    /**
     * testing game destroy when not authenticated
     *
     * @return void
     */
    public function testGameDestroyRouteNoAuth()
    {
        $game = Game::create([
            'name' => 'foo',
            'desc' => 'bar',
            'age' => '11/13 ans',
            'icon' => null,
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('games', ['name' => 'foo']);

        $response = $this
            ->call(
                'DELETE',
                '/game/destroy/' . $game->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this->assertDatabaseHas('games', ['name' => 'foo']);
    }

    /**
     * testing game edit when authenticated
     *
     * @return void
     */
    public function testGameEditRouteAuth()
    {
        $game = Game::create([
            'name' => 'foo',
            'desc' => 'bar',
            'age' => '11/13 ans',
            'icon' => null,
            'city_id' => $this->city->id
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/game/edit/' . $game->id);

        $response
            ->assertStatus(200)
            ->assertViewHas('game');

        $current = $response->original->getData()['game'];
        $this->assertInstanceOf('App\Game', $current);
    }

    /**
     * testing game edit when not authenticated
     *
     * @return void
     */
    public function testGameEditRouteNoAuth()
    {
        $game = Game::create([
            'name' => 'foo',
            'desc' => 'bar',
            'age' => '11/13 ans',
            'icon' => null,
            'city_id' => $this->city->id
        ]);

        $response = $this->get('/game/edit/' . $game->id);
        $response
            ->assertRedirect('/login')
            ->assertStatus(302);
    }

    /**
     * testing game update when authenticated
     *
     * @return void
     */
    public function testGameUpdateRouteAuth()
    {
        $game = Game::create([
            'name' => 'foo',
            'desc' => 'bar',
            'age' => '11/13 ans',
            'icon' => null,
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('games', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'PUT',
                '/game/update/' . $game->id,
                [
                    'name' => 'bar',
                    'desc' => 'foo',
                    'age' => '9/11 ans',
                    'icon' => null,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city/' . $this->city->id . '/game')
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this
            ->assertDatabaseHas('games', ['name' => 'bar'])
            ->assertDatabaseMissing('games', ['name' => 'foo']);
    }

    /**
     * testing game update when not authenticated
     *
     * @return void
     */
    public function testGameUpdateRouteNoAuth()
    {
        $game = Game::create([
            'name' => 'foo',
            'desc' => 'bar',
            'age' => '11/13 ans',
            'icon' => null,
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('games', ['name' => 'foo']);

        $response = $this
            ->call(
                'PUT',
                '/game/update/' . $game->id,
                [
                    'name' => 'bar',
                    'desc' => 'foo',
                    'age' => '9/11 ans',
                    'icon' => null,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this
            ->assertDatabaseMissing('games', ['name' => 'bar'])
            ->assertDatabaseHas('games', ['name' => 'foo']);
    }

    /**
     * testing game update with all fields invalid
     */
    public function testGameUpdateRouteInvalidFields()
    {
        $game = Game::create([
            'name' => 'foo',
            'desc' => 'bar',
            'age' => '11/13 ans',
            'icon' => null,
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('games', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->from('/game/edit/'.$game->id)
            ->call(
                'PUT',
                '/game/update/' . $game->id,
                [
                    'name' => null,
                    'desc' => null,
                    'age' => 'foo',
                    'icon' => -1,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/game/edit/' . $game->id)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'desc', 'age', 'icon']);


        $this->assertDatabaseMissing('games', ['name' => null]);
    }

    public function testGamePublishRouteToOnline()
    {

    }

    public function testGamePublishRouteToOffline()
    {

    }
}
