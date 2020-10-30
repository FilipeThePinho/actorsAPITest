<?php

namespace Tests\Feature;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('genres.index'));

        $response->assertStatus(200);
    }

    public function testStoreSuccess()
    {
        $factory = factory(Genre::class)->make()->toArray();

        $response = $this->post(route('genres.store'), $factory);

        $response->assertStatus(201);
    }

    public function testStoreFailure()
    {
        $factory = factory(Genre::class)->make()->toArray();
        unset($factory['name']);

        $response = $this->post(route('genres.store'), $factory);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function testShowSuccess()
    {
        $factory = factory(Genre::class)->create();

        $response = $this->get(route('genres.show', [$factory->id]));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $factory->name]);
    }

    public function testShowFailure()
    {
        $factory = factory(Genre::class)->make()->toArray();

        $response = $this->get(route('genres.show', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testUpdateSuccess()
    {
        $factory = factory(Genre::class)->create()->toArray();
        $factory['name'] = "UpdateTest";

        $response = $this->put(route('genres.update', [$factory['id']]), $factory);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'UpdateTest']);
    }

    public function testUpdateFailure()
    {
        $factory = factory(Genre::class)->create()->toArray();

        $response = $this->put(route('genres.update', ['123']), $factory);

        $response->assertStatus(422);
    }

    public function testDeleteSuccess()
    {
        $factory = factory(Genre::class)->create()->toArray();

        $response = $this->delete(route('genres.destroy', [$factory['id']]));

        $response->assertStatus(200);
        $this->assertDeleted('genres', $factory);
    }

    public function testDeleteFailure()
    {
        factory(Genre::class)->create();

        $response = $this->delete(route('genres.destroy', ['22']));

        $response->assertStatus(404);
    }
}
