<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Movie::class, function (Faker $faker) {
    return [
        'id' => $faker->uuid,
        'name'      => $faker->company,
        'genre_id'  => factory(\App\Models\Genre::class)->create()->id,
        'year'  => $faker->year,
        'synopsis'  => $faker->text('50'),
        'runtime'  => $faker->numberBetween(60,150),
        'released_at'  => $faker->date(),
        'cost'  => $faker->numberBetween(100000,1500000) * 0.01,
        'created_at'    => now(),
    ];
});
