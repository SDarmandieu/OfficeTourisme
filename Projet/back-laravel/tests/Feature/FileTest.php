<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\User;
use App\City;
use App\File;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class FileTest extends TestCase
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
        $this->city = City::create([
            'name' => 'foo',
            'lat' => '68.124',
            'lon' => '24.176'
        ]);
        $this->user = factory(User::class)->create();
    }

    /**
     * testing image index route when authenticated
     * @return void
     */
    public function testImageIndexRoute()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/city/' . $this->city->id . '/files/image');

        $response
            ->assertStatus(200)
            ->assertViewHas('city');

        $current = $response->original->getData()['city'];
        $this->assertInstanceOf('App\City', $current);
    }

    /**
     * testing audio index route when authenticated
     * @return void
     */
    public function testAudioIndexRoute()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/city/' . $this->city->id . '/files/audio');

        $response
            ->assertStatus(200)
            ->assertViewHas('city');

        $current = $response->original->getData()['city'];
        $this->assertInstanceOf('App\City', $current);
    }

    /**
     * testing video index route when authenticated
     * @return void
     */
    public function testVideoIndexRoute()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/city/' . $this->city->id . '/files/video');

        $response
            ->assertStatus(200)
            ->assertViewHas('city');

        $current = $response->original->getData()['city'];
        $this->assertInstanceOf('App\City', $current);
    }

    /**
     * testing file create when authenticated
     *
     * @return void
     */
    public function testFileCreateRoute()
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/city/' . $this->city->id . '/file/create');

        $response
            ->assertStatus(200)
            ->assertViewHas('city');
    }

    /**
     * testing image store when authenticated
     *
     * @return void
     */
    public function testImageStoreRoute()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.jpg');

        $response = $this
            ->actingAs($this->user)
            ->from('/city/' . $this->city->id . '/file/create')
            ->post('/city/' . $this->city->id . '/file/store',
                [
                    'file' => $file,
                    'alt' => 'foo',
                    'imagetype' => 1
                ]);

        Storage::disk('public')->assertExists('/files/image/' . $file->hashName());

        $response
            ->assertRedirect('/city/' . $this->city->id . '/files/image')
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('files', ['filename' => 'test.jpg']);
    }

    /**
     * testing image store with invalid fields
     *
     * @return void
     */
    public function testImageStoreRouteInvalidFields()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.jpg', 20000);

        $response = $this
            ->actingAs($this->user)
            ->from('/city/' . $this->city->id . '/file/create')
            ->post('/city/' . $this->city->id . '/file/store',
                [
                    'file' => $file,
                    'alt' => null,
                    'imagetype' => -1
                ]);

        Storage::disk('public')->assertMissing('/files/image/' . $file->hashName());

        $response
            ->assertLocation('/city/' . $this->city->id . '/file/create')
            ->assertStatus(302)
            ->assertSessionHasErrors(['file', 'alt', 'imagetype']);

        $this->assertDatabaseMissing('files', ['filename' => 'test.jpg']);
    }

    /**
     * testing video store when authenticated
     *
     * @return void
     */
    public function testVideoStoreRoute()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.mpeg');

        $response = $this
            ->actingAs($this->user)
            ->from('/city/' . $this->city->id . '/file/create')
            ->post('/city/' . $this->city->id . '/file/store',
                [
                    'file' => $file,
                    'alt' => 'foo',
                    'imagetype' => null
                ]);

        Storage::disk('public')->assertExists('/files/video/' . $file->hashName());

        $response
            ->assertRedirect('/city/' . $this->city->id . '/files/video')
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('files', ['filename' => 'test.mpeg']);
    }

    /**
     * testing video store with invalid fields
     *
     * @return void
     */
    public function testVideoStoreRouteInvalidFields()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.avi');

        $response = $this
            ->actingAs($this->user)
            ->from('/city/' . $this->city->id . '/file/create')
            ->post('/city/' . $this->city->id . '/file/store',
                [
                    'file' => $file,
                    'alt' => null,
                    'imagetype' => -1
                ]);

        Storage::disk('public')->assertMissing('/files/video/' . $file->hashName());

        $response
            ->assertLocation('/city/' . $this->city->id . '/file/create')
            ->assertStatus(302)
            ->assertSessionHasErrors(['file', 'alt', 'imagetype']);

        $this->assertDatabaseMissing('files', ['filename' => 'test.avi']);
    }

    /**
     * testing audio store when authenticated
     *
     * @return void
     */
    public function testAudioStoreRoute()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.mp3');

        $response = $this
            ->actingAs($this->user)
            ->from('/city/' . $this->city->id . '/file/create')
            ->post('/city/' . $this->city->id . '/file/store',
                [
                    'file' => $file,
                    'alt' => 'foo',
                    'imagetype' => null
                ]);

        Storage::disk('public')->assertExists('/files/audio/' . $file->hashName());

        $response
            ->assertRedirect('/city/' . $this->city->id . '/files/audio')
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('files', ['filename' => 'test.mp3']);
    }

    /**
     * testing audio store with invalid fields
     *
     * @return void
     */
    public function testAudioStoreRouteInvalidFields()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test.avi');

        $response = $this
            ->actingAs($this->user)
            ->from('/city/' . $this->city->id . '/file/create')
            ->post('/city/' . $this->city->id . '/file/store',
                [
                    'file' => $file,
                    'alt' => null,
                    'imagetype' => -1
                ]);

        Storage::disk('public')->assertMissing('/files/audio/' . $file->hashName());

        $response
            ->assertLocation('/city/' . $this->city->id . '/file/create')
            ->assertStatus(302)
            ->assertSessionHasErrors(['file', 'alt', 'imagetype']);

        $this->assertDatabaseMissing('files', ['filename' => 'test.avi']);
    }

    /**
     * testing image edit when authenticated
     *
     * @return void
     */
    public function testImageEditRoute()
    {
        Storage::fake('public');

        $upload = UploadedFile::fake()->create('test.jpg');
        $type = 'image';
        $path = Storage::disk('public')->putFile('files/' . $type, $upload, 'public');

        $file = File::create([
            'filename' => $upload->name,
            'path' => $path,
            'type' => $type,
            'extension' => 'jpg',
            'alt' => 'foo',
            'city_id' => $this->city->id,
            'imagetype_id' => 3
        ]);

        $response = $this
            ->actingAs($this->user)
            ->get('/file/edit/' . $file->id);

        $response
            ->assertStatus(200)
            ->assertViewHas('file');

        $current = $response->original->getData()['file'];
        $this->assertInstanceOf('App\File', $current);
    }

    /**
     * testing image update when authenticated
     *
     * @return void
     */
    public function testImageUpdateRoute()
    {
        Storage::fake('public');

        $upload = UploadedFile::fake()->create('test.jpg');
        $type = 'image';
        $path = Storage::disk('public')->putFile('files/' . $type, $upload, 'public');

        $file = File::create([
            'filename' => $upload->name,
            'path' => $path,
            'type' => $type,
            'extension' => 'jpg',
            'alt' => 'foo',
            'city_id' => $this->city->id,
            'imagetype_id' => 3
        ]);

        $this->assertDatabaseHas('files', ['filename' => 'test.jpg']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'PUT',
                '/file/update/' . $file->id,
                [
                    'alt' => 'bar',
                    'imagetype' => 2,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city/' . $this->city->id . '/files/image')
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this
            ->assertDatabaseHas('files', ['alt' => 'bar'])
            ->assertDatabaseMissing('files', ['alt' => 'foo']);
    }

    /**
     * testing image update with invalid fields
     *
     * @return void
     */
    public function testImageUpdateRouteInvalidFields()
    {
        Storage::fake('public');

        $upload = UploadedFile::fake()->create('test.jpg');
        $type = 'image';
        $path = Storage::disk('public')->putFile('files/' . $type, $upload, 'public');

        $file = File::create([
            'filename' => $upload->name,
            'path' => $path,
            'type' => $type,
            'extension' => 'jpg',
            'alt' => 'foo',
            'city_id' => $this->city->id,
            'imagetype_id' => 3
        ]);

        $this->assertDatabaseHas('files', ['filename' => 'test.jpg']);

        $response = $this
            ->actingAs($this->user)
            ->from('/file/edit/' . $file->id)
            ->call(
                'PUT',
                '/file/update/' . $file->id,
                [
                    'alt' => null,
                    'imagetype' => -1,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/file/edit/' . $file->id)
            ->assertStatus(302)
            ->assertSessionHasErrors(['alt', 'imagetype']);

        $this
            ->assertDatabaseHas('files', ['alt' => 'foo'])
            ->assertDatabaseMissing('files', ['alt' => 'bar']);
    }

    /**
     * testing audio_video update when authenticated
     *
     * @return void
     */
    public function testAudioVideoUpdateRoute()
    {
        Storage::fake('public');

        $upload = UploadedFile::fake()->create('test.mp4');
        $type = 'video';
        $path = Storage::disk('public')->putFile('files/' . $type, $upload, 'public');

        $file = File::create([
            'filename' => $upload->name,
            'path' => $path,
            'type' => $type,
            'extension' => 'mp4',
            'alt' => 'foo',
            'city_id' => $this->city->id,
            'imagetype_id' => null
        ]);

        $this->assertDatabaseHas('files', ['filename' => 'test.mp4']);

        $response = $this
            ->actingAs($this->user)
            ->call(
                'PUT',
                '/file/update/' . $file->id,
                [
                    'alt' => 'bar',
                    'imagetype' => null,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city/' . $this->city->id . '/files/video')
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this
            ->assertDatabaseHas('files', ['alt' => 'bar'])
            ->assertDatabaseMissing('files', ['alt' => 'foo']);
    }

    /**
     * testing audio_video update with invalid fields
     *
     * @return void
     */
    public function testAudioVideoUpdateRouteInvalidFields()
    {
        Storage::fake('public');

        $upload = UploadedFile::fake()->create('test.mp3');
        $type = 'audio';
        $path = Storage::disk('public')->putFile('files/' . $type, $upload, 'public');

        $file = File::create([
            'filename' => $upload->name,
            'path' => $path,
            'type' => $type,
            'extension' => 'mp3',
            'alt' => 'foo',
            'city_id' => $this->city->id,
            'imagetype_id' => null
        ]);

        $this->assertDatabaseHas('files', ['filename' => 'test.mp3']);

        $response = $this
            ->actingAs($this->user)
            ->from('/file/edit/' . $file->id)
            ->call(
                'PUT',
                '/file/update/' . $file->id,
                [
                    'alt' => null,
                    'imagetype' => -1,
                    '_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/file/edit/' . $file->id)
            ->assertStatus(302)
            ->assertSessionHasErrors(['alt', 'imagetype']);

        $this
            ->assertDatabaseHas('files', ['alt' => 'foo'])
            ->assertDatabaseMissing('files', ['alt' => 'bar']);
    }


    /**
     * testing file destroy when authenticated
     *
     * @return void
     */
    public function testFileDestroyRoute()
    {
        Storage::fake('public');

        $upload = UploadedFile::fake()->create('test.mp3');
        $type = 'audio';
        $path = Storage::disk('public')->putFile('files/' . $type, $upload, 'public');

        $file = File::create([
            'filename' => $upload->name,
            'path' => $path,
            'type' => $type,
            'extension' => 'mp3',
            'alt' => 'foo',
            'city_id' => $this->city->id,
            'imagetype_id' => null
        ]);

        $this->assertDatabaseHas('files', ['filename' => 'test.mp3']);
        Storage::disk('public')->assertExists('/files/' . $type . '/' . $upload->hashName());


        $response = $this
            ->actingAs($this->user)
            ->call(
                'DELETE',
                '/file/destroy/' . $file->id,
                ['_token' => csrf_token()]
            );

        $response
            ->assertRedirect('/city/' . $this->city->id . '/files/' . $type)
            ->assertStatus(302)
            ->assertSessionHas('success')
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('files', ['filename' => 'test.mp3']);
        Storage::disk('public')->assertMissing('/files/' . $type . '/' . $upload->hashName());
    }
}
