<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Actor::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name'      => $faker->firstName,
        'bio'  => $faker->text('50'),
        'born_at'  => $faker->date(),
        'created_at'    => now(),
    ];
});
