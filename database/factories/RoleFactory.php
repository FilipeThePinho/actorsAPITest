<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Role::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name'      => $faker->firstName,
        'movie_id'  => factory(\App\Models\Movie::class)->create()->id,
        'actor_id'  => factory(\App\Models\Actor::class)->create()->id,
        'created_at'    => now(),
    ];
});
