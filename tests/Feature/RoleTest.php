<?php

namespace Tests\Feature;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get(route('roles.index'));

        $response->assertStatus(200);
    }

    public function testStoreSuccess()
    {
        $factory = factory(Role::class)->make()->toArray();

        $response = $this->post(route('roles.store'), $factory);

        $response->assertStatus(201);
    }

    public function testStoreFailure()
    {
        $factory = factory(Role::class)->make()->toArray();
        unset($factory['name']);

        $response = $this->post(route('roles.store'), $factory);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function testShowSuccess()
    {
        $factory = factory(Role::class)->create();

        $response = $this->get(route('roles.show', [$factory->id]));

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $factory->name]);
    }

    public function testShowFailure()
    {
        $factory = factory(Role::class)->make()->toArray();

        $response = $this->get(route('roles.show', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testUpdateSuccess()
    {
        $factory = factory(Role::class)->create()->toArray();
        $factory['name'] = "UpdateTest";

        $response = $this->put(route('roles.update', [$factory['id']]), $factory);

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'UpdateTest']);
    }

    public function testUpdateFailure()
    {
        $factory = factory(Role::class)->create()->toArray();

        $response = $this->put(route('roles.update', ['123']), $factory);

        $response->assertStatus(404);
    }

    public function testDeleteSuccess()
    {
        $factory = factory(Role::class)->create()->toArray();

        $response = $this->delete(route('roles.destroy', [$factory['id']]));

        $response->assertStatus(200);
        $this->assertDeleted('roles', $factory);
    }

    public function testDeleteFailure()
    {
        factory(Role::class)->create();

        $response = $this->delete(route('roles.destroy', ['22']));

        $response->assertStatus(404);
    }
}
