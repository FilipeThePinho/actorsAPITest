<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Genre::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name' => $faker->colorName,
        'created_at'    => now(),
    ];
});
