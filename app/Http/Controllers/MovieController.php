<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasFetchAllRenderCapabilities;
use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieController extends Controller
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
        $this->setGetAllBuilder(Movie::query());
        $this->setGetAllOrdering('name', 'asc');
        $this->parseRequestConditions($request);
        return new ResourceCollection($this->getAll()->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param MovieRequest $request
     * @return \App\Http\Resources\Movie
     */
    public function store(MovieRequest $request)
    {
        $movie = new Movie($request->validated());
        $movie->save();

        return new \App\Http\Resources\Movie($movie);
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
            $movie = Movie::findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json([], 404);
        }

        return new \App\Http\Resources\Movie($movie);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MovieRequest $request
     * @param $id
     * @return \App\Http\Resources\Movie
     */
    public function update(MovieRequest $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $movie->fill($request->validated());
        $movie->save();

        return new \App\Http\Resources\Movie($movie);
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
        $movie = Movie::findOrFail($id);

        $movie->delete();

        return response()->json();
    }
}
