<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasFetchAllRenderCapabilities;
use App\Http\Requests\ActorRequest;
use App\Models\Actor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ActorController extends Controller
{

    use HasFetchAllRenderCapabilities;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $this->setGetAllBuilder(Actor::query());
        $this->setGetAllOrdering('name', 'asc');
        $this->parseRequestConditions($request);
        return new ResourceCollection($this->getAll()->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param ActorRequest $request
     * @return \App\Http\Resources\Actor
     */
    public function store(ActorRequest $request)
    {
        $actor = new Actor($request->validated());
        $actor->save();

        return new \App\Http\Resources\Actor($actor);
    }

    /**
     * Show the resource
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        try {
            $actor = Actor::findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json([], 404);
        }

        return new \App\Http\Resources\Actor($actor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ActorRequest $request
     * @param $id
     * @return \App\Http\Resources\Actor
     */
    public function update(ActorRequest $request, $id)
    {
        $actor = Actor::findOrFail($id);

        $actor->fill($request->validated());
        $actor->save();

        return new \App\Http\Resources\Actor($actor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $actor = Actor::findOrFail($id);

        $actor->delete();

        return response()->json();
    }

    /**
     * Get movies starred by the given actor.
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function starred_movies($id)
    {
        try {
            $actor = Actor::findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json([], 404);
        }

        $rolesFiltered = $actor->roles()->groupBy('movie_id')->get();

        $movies = [];
        foreach ($rolesFiltered as $role){
            $movies[] = $role->movie->only(['name','id']);
        }

        return new \App\Http\Resources\Actor($movies);
    }

    /**
     * Get actors number of movies in genres.
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function genre_list($id)
    {
        try {
            $actor = Actor::findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json([], 404);
        }

        $rolesFiltered = $actor->roles()->groupBy('movie_id')->get();

        $movies = [];
        foreach ($rolesFiltered as $role){
            $genreID = $role->movie->genre_id;

            $movies[$genreID] = $movies[$genreID] ?? 0;
            $movies[$genreID]++;
        }

        $actor->movies_by_genre = $movies;

        return new \App\Http\Resources\Actor($actor);
    }

    /**
     * Get actor favourite genre.
     *
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function favourite_genre($id)
    {
        try {
            $actor = Actor::findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json([], 404);
        }

        $rolesFiltered = $actor->roles()->get();

        $movies = [];

        foreach ($rolesFiltered as $role){
            $genreID = $role->movie->genre_id;

            $movies[$genreID] = $movies[$genreID] ?? 0;
            $movies[$genreID]++;
        }

        krsort($movies);

        return response()->json([array_key_first($movies)]);
    }
}
