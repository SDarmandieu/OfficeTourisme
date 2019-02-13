<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\City;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RouteTest extends TestCase
{
    use DatabaseTransactions;

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
    }

    /**
     * testing home route when authenticated
     *
     * @return void
     */
    public function testHomeRoute()
    {
        $response = $this
            ->get('/');
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
        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * testing city index when authenticated
     *
     * @return void
     */
    public function testCityRouteAuth()
    {
        $response = $this
            ->actingAs($this->user)
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
        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * testing city create when authenticated
     *
     * @return void
     */
    public function testCityCreateRouteAuth()
    {
        $response = $this
            ->actingAs($this->user)
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
        $response = $this
            ->actingAs($this->user)
            ->from('/city/create')
            ->post('/city/store', [
                'name' => 'foo',
                'latitude' => '42',
                'longitude' => '13'
            ]);

        $response
            ->assertRedirect('/city')
            ->assertStatus(302);

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

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this->assertDatabaseMissing('cities', ['name' => 'foo']);
    }

    /**
     * testing city store with invalid fields
     *
     * @return void
     */
    public function testCityStoreRouteInvalidFields()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/city/create')
            ->post('/city/store', [
                'name' => null,
                'latitude' => '-1000',
                'longitude' => 'toto'
            ]);

        $response
            ->assertRedirect('/city/create')
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'latitude', 'longitude']);

        $this->assertDatabaseMissing('cities', ['name' => null]);
    }

    /**
     * testing city destroy when authenticated
     *
     * @return void
     */
    public function testCityDestroyRouteAuth()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $this->assertDatabaseHas('cities', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'DELETE',
                '/city/destroy/' . $city->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city')
            ->assertStatus(302);

        $this->assertDatabaseMissing('cities', ['name' => 'foo']);
    }

    /**
     * testing city destroy when not authenticated
     *
     * @return void
     */
    public function testCityDestroyRouteNoAuth()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $this->assertDatabaseHas('cities', ['name' => 'foo']);

        $response = $this
            ->call(
                'DELETE',
                '/city/destroy/' . $city->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this->assertDatabaseHas('cities', ['name' => 'foo']);
    }

    /**
     * testing city edit when authenticated
     *
     * @return void
     */
    public function testCityEditRouteAuth()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/city/edit/' . $city->id);

        $response
            ->assertStatus(200)
            ->assertViewHas('city');

        $current = $response->original->getData()['city'];
        $this->assertInstanceOf('App\City', $current);
    }

    /**
     * testing city edit when not authenticated
     *
     * @return void
     */
    public function testCityEditRouteNoAuth()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $response = $this
            ->get('/city/edit/' . $city->id);
        $response
            ->assertRedirect('/login')
            ->assertStatus(302);
    }

    /**
     * testing city update when authenticated
     *
     * @return void
     */
    public function testCityUpdateRouteAuth()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $this->assertDatabaseHas('cities', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'PUT',
                '/city/update/' . $city->id,
                [
                    'name' => 'bar',
                    'latitude' => '50',
                    'longitude' => '40',
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city')
            ->assertStatus(302);

        $this
            ->assertDatabaseHas('cities', ['name' => 'bar'])
            ->assertDatabaseMissing('cities', ['name' => 'foo']);
    }

    /**
     * testing city update when not authenticated
     *
     * @return void
     */
    public function testCityUpdateRouteNoAuth()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $this->assertDatabaseHas('cities', ['name' => 'foo']);

        $response = $this
            ->call(
                'PUT',
                '/city/update/' . $city->id,
                [
                    'name' => 'bar',
                    'latitude' => '50',
                    'longitude' => '40',
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this
            ->assertDatabaseMissing('cities', ['name' => 'bar'])
            ->assertDatabaseHas('cities', ['name' => 'foo']);
    }

    /**
     * testing city update with invalid fields
     *
     * @return void
     */
    public function testCityUpdateRouteInvalidFields()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $this->assertDatabaseHas('cities', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->from('/city/edit/' . $city->id)
            ->call(
                'PUT',
                '/city/update/' . $city->id,
                [
                    'name' => null,
                    'latitude' => -1000,
                    'longitude' => 'foobar',
                    '_token' => csrf_token()]
            );
        $response
            ->assertRedirect('/city/edit/' . $city->id)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'latitude', 'longitude']);

        $this->assertDatabaseMissing('cities', ['name' => null]);
    }

    /**
     * testing a specific city home page when authenticated
     */
    public function testCityHomeRouteAuth()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/city/' . $city->id);

        $response
            ->assertStatus(200)
            ->assertViewHas('city');

        $current = $response->original->getData()['city'];
        $this->assertInstanceOf('App\City', $current);
    }

    /**
     * testing a specific city home page when authenticated
     */
    public function testCityHomeRouteNoAuth()
    {
        $city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);

        $response = $this->get('/city/' . $city->id);

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);
    }
}
