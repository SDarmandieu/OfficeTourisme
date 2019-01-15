<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\City;
use App\Point;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PointTest extends TestCase
{
    use DatabaseTransactions;

    protected $city;

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
        $this->city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);
    }

    /**
     * testing point index route when authenticated
     * @return void
     */
    public function testPointIndexRouteAuth()
    {
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($user)
            ->get('/city/' . $this->city->id . '/point');

        $response
            ->assertStatus(200)
            ->assertViewHas('city');

        $current = $response->original->getData()['city'];
        $this->assertInstanceOf('App\City', $current);
    }

    /**
     * testing point index route when not authenticated
     * @return void
     */
    public function testPointIndexRouteNoAuth()
    {
        $response = $this->get('/city/' . $this->city->id . '/point');

        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * testing point create when authenticated
     *
     * @return void
     */
    public function testPointCreateRouteAuth()
    {
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($user)
            ->get('/city/' . $this->city->id . '/point/create');
        $response
            ->assertStatus(200)
            ->assertViewHas('city');
    }

    /**
     * testing point create when not authenticated
     *
     * @return void
     */
    public function testPointCreateRouteNoAuth()
    {
        $response = $this->get('/city/' . $this->city->id . '/point/create');
        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * testing point store when authenticated
     *
     * @return void
     */
    public function testPointStoreRouteAuth()
    {
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($user)
            ->from('/city/' . $this->city->id . '/point')
            ->post('/city/' . $this->city->id . '/point/store', [
                'desc' => 'foo',
                'latitude' => '42',
                'longitude' => '13'
            ]);

        $response
            ->assertRedirect('/city/' . $this->city->id . '/point')
            ->assertStatus(302);

        $this->assertDatabaseHas('points', ['desc' => 'foo']);
    }

    /**
     * testing point store when not authenticated
     *
     * @return void
     */
    public function testPointStoreRouteNoAuth()
    {
        $response = $this
            ->from('/city/' . $this->city->id . '/point')
            ->post('/city/' . $this->city->id . '/point/store', [
                'desc' => 'foo',
                'latitude' => '42',
                'longitude' => '13'
            ]);

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this->assertDatabaseMissing('points', ['desc' => 'foo']);
    }

    /**
     * testing point destroy when authenticated
     *
     * @return void
     */
    public function testPointDestroyRouteAuth()
    {
        $user = factory(User::class)->create();

        $point = Point::create([
            'desc' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176',
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('points', ['desc' => 'foo']);

        $response = $this
            ->actingAs($user)
            ->call(
                'DELETE',
                '/point/destroy/' . $point->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city/' . $this->city->id . '/point')
            ->assertStatus(302);

        $this->assertDatabaseMissing('points', ['desc' => 'foo']);
    }

    /**
     * testing point destroy when not authenticated
     *
     * @return void
     */
    public function testPointDestroyRouteNoAuth()
    {
        $point = Point::create([
            'desc' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176',
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('points', ['desc' => 'foo']);

        $response = $this
            ->call(
                'DELETE',
                '/point/destroy/' . $point->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this->assertDatabaseHas('points', ['desc' => 'foo']);
    }

    /**
     * testing point edit when authenticated
     *
     * @return void
     */
    public function testPointEditRouteAuth()
    {
        $point = Point::create([
            'desc' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176',
            'city_id' => $this->city->id
        ]);

        $user = factory(User::class)->create();
        $response = $this
            ->actingAs($user)
            ->get('/point/edit/' . $point->id);

        $response
            ->assertStatus(200)
            ->assertViewHas('point');

        $current = $response->original->getData()['point'];
        $this->assertInstanceOf('App\Point', $current);
    }

    /**
     * testing point edit when not authenticated
     *
     * @return void
     */
    public function testPointEditRouteNoAuth()
    {
        $point = Point::create([
            'desc' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176',
            'city_id' => $this->city->id
        ]);

        $response = $this->get('/point/edit/' . $point->id);
        $response
            ->assertRedirect('/login')
            ->assertStatus(302);
    }

    /**
     * testing point update when authenticated
     *
     * @return void
     */
    public function testCityUpdateRouteAuth()
    {
        $user = factory(User::class)->create();

        $point = Point::create([
            'desc' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176',
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('points', ['desc' => 'foo']);

        $response = $this
            ->actingAs($user)
            ->call(
                'PUT',
                '/point/update/' . $point->id,
                [
                    'desc' => 'bar',
                    'latitude' => '50',
                    'longitude' => '40',
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city/' . $this->city->id . '/point')
            ->assertStatus(302);

        $this
            ->assertDatabaseHas('points', ['desc' => 'bar'])
            ->assertDatabaseMissing('points', ['desc' => 'foo']);
    }

    /**
     * testing city update when not authenticated
     *
     * @return void
     */
    public function testCityUpdateRouteNoAuth()
    {
        $point = Point::create([
            'desc' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176',
            'city_id' => $this->city->id
        ]);

        $this->assertDatabaseHas('points', ['desc' => 'foo']);

        $response = $this
            ->call(
                'PUT',
                '/point/update/' . $point->id,
                [
                    'desc' => 'bar',
                    'latitude' => '50',
                    'longitude' => '40',
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this
            ->assertDatabaseMissing('points', ['desc' => 'bar'])
            ->assertDatabaseHas('points', ['desc' => 'foo']);
    }
}
