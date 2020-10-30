<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasFetchAllRenderCapabilities;
use App\Http\Requests\RoleRequest;
use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class RoleController extends Controller
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
        $this->setGetAllBuilder(Role::query());
        $this->setGetAllOrdering('name', 'asc');
        $this->parseRequestConditions($request);
        return new ResourceCollection($this->getAll()->paginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param RoleRequest $request
     * @return \App\Http\Resources\Role
     */
    public function store(RoleRequest $request)
    {
        $role = new Role($request->validated());
        $role->save();

        return new \App\Http\Resources\Role($role);
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
            $role = Role::findOrFail($id);
        } catch (ModelNotFoundException $e){
            return response()->json([], 404);
        }

        return new \App\Http\Resources\Role($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param $id
     * @return \App\Http\Resources\Role
     */
    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);

        $role->fill($request->validated());
        $role->save();

        return new \App\Http\Resources\Role($role);
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
        $role = Role::findOrFail($id);

        $role->delete();

        return response()->json();
    }
}
