<?php

namespace Tests\Feature;

use App\Models\Actor;
use App\Models\Movie;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('actors.index'));

        $response->assertStatus(200);
    }

    public function testStoreSuccess()
    {
        $factory = factory(Actor::class)->make()->toArray();

        $response = $this->post(route('actors.store'), $factory);

        $response->assertStatus(201);
    }

    public function testStoreFailure()
    {
        $factory = factory(Actor::class)->make()->toArray();
        unset($factory['name']);

        $response = $this->post(route('actors.store'), $factory);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function testShowSuccess()
    {
        $factory = factory(Actor::class)->create();

        $response = $this->get(route('actors.show', [$factory->id]));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $factory->name]);
    }

    public function testShowFailure()
    {
        $factory = factory(Actor::class)->make()->toArray();

        $response = $this->get(route('actors.show', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testUpdateSuccess()
    {
        $factory = factory(Actor::class)->create()->toArray();
        $factory['name'] = "UpdateTest";

        $response = $this->put(route('actors.update', [$factory['id']]), $factory);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'UpdateTest']);
    }

    public function testUpdateFailure()
    {
        $factory = factory(Actor::class)->create()->toArray();

        $response = $this->put(route('actors.update', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testDeleteSuccess()
    {
        $factory = factory(Actor::class)->create()->toArray();

        $response = $this->delete(route('actors.destroy', [$factory['id']]));

        $response->assertStatus(200);
        $this->assertDeleted('actors', $factory);
    }

    public function testDeleteFailure()
    {
        factory(Actor::class)->create();

        $response = $this->delete(route('actors.destroy', ['22']));

        $response->assertStatus(404);
    }

    public function testStarredMoviesSuccess()
    {
        $factoryActor = factory(Actor::class)->create();
        $factoryMovie = factory(Movie::class)->create();

        //To generate second movie not related
        $factoryMovieMissing = factory(Movie::class)->create();

        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $factoryMovie->id]);

        //Double starring
        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $factoryMovie->id]);

        $response = $this->get(route('actors.starred_movies', [$factoryActor->id]));

        $response->assertStatus(200);
        $response->assertJsonMissing(['id' => $factoryMovieMissing->id]);
        $response->assertJsonFragment(['id' => $factoryMovie->id]);

    }

    public function testStarredMoviesFailure()
    {
        $factory = factory(Actor::class)->make()->toArray();

        $response = $this->get(route('actors.starred_movies', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testMoviesByGenreSuccess()
    {
        $factoryActor = factory(Actor::class)->create();
        $factoryMovie = factory(Movie::class)->create();
        $factoryMovie2 = factory(Movie::class)->create(['genre_id' => $factoryMovie->genre_id]); //2 of same genre
        $anotherMovie = factory(Movie::class)->create(); //random genre

        //To generate second movie not related
        $factoryMovieMissing = factory(Movie::class)->create();

        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $factoryMovie->id]);
        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $factoryMovie->id]); //Double starring

        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $factoryMovie2->id]);
        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $anotherMovie->id]);


        $response = $this->get(route('actors.genre_list', [$factoryActor->id]));

        $response->assertStatus(200);

        $response->assertJsonStructure(
            [
                "data" => [
                    'id',
                    'name',
                    'bio',
                    'born_at',
                    'movies_by_genre'
                ]
            ]
        );

        $response->assertJsonMissing(['movies_by_genre' => $factoryMovieMissing->id]);
        $response->assertJsonFragment([$factoryMovie->genre_id => 2]);
        $response->assertJsonFragment([$anotherMovie->genre_id => 1]);

    }

    public function testMoviesByGenreFailure()
    {
        $factory = factory(Actor::class)->make()->toArray();

        $response = $this->get(route('actors.genre_list', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testActorFavouriteSuccess()
    {
        $factoryActor = factory(Actor::class)->create();
        $factoryMovie = factory(Movie::class)->create();
        $factoryMovie2 = factory(Movie::class)->create(['genre_id' => $factoryMovie->genre_id]); //2 of same genre
        $anotherMovie = factory(Movie::class)->create(); //random genre

        //To generate second movie not related
        factory(Movie::class)->create();

        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $factoryMovie->id]);
        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $factoryMovie->id]); //Double starring

        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $factoryMovie2->id]);
        factory(Role::class)->create(['actor_id' => $factoryActor->id, 'movie_id' => $anotherMovie->id]);


        $response = $this->get(route('actors.favourite_genre', [$factoryActor->id]));

        $response->assertStatus(200);
        $response->assertJsonFragment([$factoryMovie->genre_id]);

    }

    public function testActorFavouriteFailure()
    {
        $factory = factory(Actor::class)->make()->toArray();

        $response = $this->get(route('actors.favourite_genre', ['123']), $factory);

        $response->assertStatus(404);
    }
}
