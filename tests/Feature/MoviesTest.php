<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoviesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('movies.index'));

        $response->assertStatus(200);
    }

    public function testStoreSuccess()
    {
        $factory = factory(Movie::class)->make()->toArray();

        $response = $this->post(route('movies.store'), $factory);

        $response->assertStatus(201);
    }

    public function testStoreFailure()
    {
        $factory = factory(Movie::class)->make()->toArray();
        unset($factory['name']);

        $response = $this->post(route('movies.store'), $factory);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function testShowSuccess()
    {
        $factory = factory(Movie::class)->create();

        $response = $this->get(route('movies.show', [$factory->id]));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $factory->name]);
    }

    public function testShowFailure()
    {
        $factory = factory(Movie::class)->make()->toArray();

        $response = $this->get(route('movies.show', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testUpdateSuccess()
    {
        $factory = factory(Movie::class)->create()->toArray();
        $factory['name'] = "UpdateTest";

        $response = $this->put(route('movies.update', [$factory['id']]), $factory);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'UpdateTest']);
    }

    public function testUpdateFailure()
    {
        $factory = factory(Movie::class)->create()->toArray();

        $response = $this->put(route('movies.update', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testDeleteSuccess()
    {
        $factory = factory(Movie::class)->create()->toArray();

        $response = $this->delete(route('movies.destroy', [$factory['id']]));

        $response->assertStatus(200);
        $this->assertDeleted('movies', $factory);
    }

    public function testDeleteFailure()
    {
        factory(Movie::class)->create();

        $response = $this->delete(route('movies.destroy', ['22']));

        $response->assertStatus(404);
    }
}
