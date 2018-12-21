<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use App\City;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RouteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Set the URL of the previous request.
     *
     * @param  string  $url
     * @return $this
     */
    public function from($url)
    {
        $this
            ->app['session']
            ->setPreviousUrl($url);

        return $this;
    }

    /**
     * testing home route status
     *
     * @return void
     */
    public function testHomeRoute()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * testing city index when not authenticated
     *
     * @return void
     */
    public function testCityRouteNoAuth()
    {
        $response = $this->get('/city');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * testing city index when authenticated
     *
     * @return void
     */
    public function testCityRouteAuth()
    {
        $user = factory(User::class)->create();
        $response = $this
            ->actingAs($user)
            ->get('/city');
        $response->assertStatus(200);
    }

    /**
     * testing city create when not authenticated
     *
     * @return void
     */
    public function testCityCreateRouteNoAuth()
    {
        $response = $this->get('/city/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /**
     * testing city create when authenticated
     *
     * @return void
     */
    public function testCityCreateRouteAuth()
    {
        $user = factory(User::class)->create();
        $response = $this
            ->actingAs($user)
            ->get('/city/create');
        $response->assertStatus(200);
    }

    /**
     * testing city store when authenticated
     *
     * @return void
     */
    public function testCityStoreRouteAuth()
    {
        $user = factory(User::class)->create();

        $response = $this
            ->actingAs($user)
            ->from('/city/create')
            ->post('/city/store', [
                'name' => 'foo',
                'latitude' => '42',
                'longitude' => '13'
            ]);

        $response->assertRedirect('/city');
        $response->assertStatus(302);

        $this->assertDatabaseHas('cities', ['name' => 'foo']);
    }

    /**
     * testing city store when not authenticated
     *
     * @return void
     */
    public function testCityStoreRouteNoAuth()
    {
        $response = $this
            ->from('/city/create')
            ->post('/city/store', [
                'name' => 'foo',
                'latitude' => '42',
                'longitude' => '13'
            ]);

        $response->assertRedirect('/login');
        $response->assertStatus(302);

        $this->assertDatabaseMissing('cities', ['name' => 'foo']);
    }

    public function testCityDestroyRouteAuth()
    {
        $user = factory(User::class)->create();

        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $this->assertDatabaseHas('cities', ['name' => 'foo']);

        $response = $this
            ->actingAs($user)
            ->call(
                'DELETE',
                '/city/destroy/'.$city->id,
                ['_token' => csrf_token()]
            );

        $response->assertRedirect('/city');
        $response->assertStatus(302);

        $this->assertDatabaseMissing('cities', ['name' => 'foo']);
    }
}
