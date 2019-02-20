<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;


class UserTest extends TestCase
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
     * testing user index when not authenticated
     *
     * @return void
     */
    public function testUserIndexRouteNoAuth()
    {
        $response = $this->get('/users');
        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * testing user index when authenticated
     *
     * @return void
     */
    public function testUserIndexRouteAuth()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/users');
        $response->assertStatus(200);
    }

    /**
     * testing user create when not authenticated
     *
     * @return void
     */
    public function testUserCreateRouteNoAuth()
    {
        $response = $this->get('/user/create');
        $response
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * testing user create when authenticated
     *
     * @return void
     */
    public function testUserCreateRouteAuth()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/user/create');
        $response->assertStatus(200);
    }

    /**
     * testing user store when authenticated
     *
     * @return void
     */
    public function testUserStoreRouteAuth()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/user/create')
            ->post('/user/store', [
                'name' => 'foo',
                'email' => 'foo@bar.com',
                'password' => 'secret'
            ]);

        $response
            ->assertRedirect('/users')
            ->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'foo',
            'email' => 'foo@bar.com'
        ]);
    }

    /**
     * testing user store when not authenticated
     *
     * @return void
     */
    public function testUserStoreRouteNoAuth()
    {
        $response = $this
            ->from('/user/create')
            ->post('/user/store', [
                'name' => 'foo',
                'email' => 'foo@bar.com',
                'password' => 'secret'
            ]);

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this->assertDatabaseMissing('users', [
            'name' => 'foo',
            'email' => 'foo@bar.com'
        ]);
    }

    /**
     * testing user store with invalid fields
     *
     * @return void
     */
    public function testUserStoreRouteInvalidFields()
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/user/create')
            ->post('/user/store', [
                'name' => null,
                'email' => '-1000',
                'password' => 'toto'
            ]);

        $response
            ->assertRedirect('/user/create')
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertDatabaseMissing('users', [
            'name' => null,
            'email' => '-1000',
        ]);
    }

    /**
     * testing user destroy when authenticated
     *
     * @return void
     */
    public function testUserDestroyRouteAuth()
    {
        $newUser = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseHas('users', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'DELETE',
                '/user/destroy/' . $newUser->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/users')
            ->assertStatus(302);

        $this->assertDatabaseMissing('users', [
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => $newUser->password
        ]);
    }

    /**
     * testing user destroy when not authenticated
     *
     * @return void
     */
    public function testUserDestroyRouteNoAuth()
    {
        $newUser = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseHas('users', ['name' => 'foo']);

        $response = $this
            ->call(
                'DELETE',
                '/user/destroy/' . $newUser->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => $newUser->password
        ]);
    }

    /**
     * testing user edit when authenticated
     *
     * @return void
     */
    public function testUserEditRouteAuth()
    {
        $newUser = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => 'secret'
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/user/edit/' . $newUser->id);

        $response
            ->assertStatus(200)
            ->assertViewHas('user');

        $current = $response->original->getData()['user'];
        $this->assertInstanceOf('App\User', $current);
    }

    /**
     * testing user edit when not authenticated
     *
     * @return void
     */
    public function testUserEditRouteNoAuth()
    {
        $newUser = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => 'secret'
        ]);

        $response = $this
            ->get('/user/edit/' . $newUser->id);
        $response
            ->assertRedirect('/login')
            ->assertStatus(302);
    }

    /**
     * testing user update when authenticated
     *
     * @return void
     */
    public function testUserUpdateRouteAuth()
    {
        $newUser = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseHas('users', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'PUT',
                '/user/update/' . $newUser->id,
                [
                    'name' => 'bar',
                    'email' => 'toto@toto.fr',
                    'password' => 'azerty',
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/users')
            ->assertStatus(302);

        $this
            ->assertDatabaseHas('users', [
                'name' => 'bar',
                'email' => 'toto@toto.fr'
            ])
            ->assertDatabaseMissing('users', [
                'name' => 'foo',
                'email' => 'foo@bar',
                'password' => 'secret'
            ]);
    }

    /**
     * testing user update when not authenticated
     *
     * @return void
     */
    public function testUserUpdateRouteNoAuth()
    {
        $newUser = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseHas('users', ['name' => 'foo']);

        $response = $this
            ->call(
                'PUT',
                '/user/update/' . $newUser->id,
                [
                    'name' => 'bar',
                    'email' => '50',
                    'password' => '40',
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/login')
            ->assertStatus(302);

        $this
            ->assertDatabaseMissing('users', ['name' => 'bar'])
            ->assertDatabaseHas('users', ['name' => 'foo']);
    }

    /**
     * testing user update with invalid fields
     *
     * @return void
     */
    public function testUserUpdateRouteInvalidFields()
    {
        $newUser = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseHas('users', ['name' => 'foo']);

        $response = $this
            ->actingAs($this->user)
            ->from('/user/edit/' . $newUser->id)
            ->call(
                'PUT',
                '/user/update/' . $newUser->id,
                [
                    'name' => null,
                    'email' => -1000,
                    'password' => 'short',
                    '_token' => csrf_token()]
            );
        $response
            ->assertRedirect('/user/edit/' . $newUser->id)
            ->assertStatus(302)
            ->assertSessionHasErrors(['name', 'email', 'password']);

        $this->assertDatabaseMissing('users', ['name' => null]);
    }
}
