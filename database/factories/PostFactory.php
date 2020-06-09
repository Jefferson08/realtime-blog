<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'category_id' => rand(1, 5),
        'title' => $faker->sentence(5),
        'description' => $faker->sentence(10),
        'body' => $faker->text(),
        'created_at' => $faker->date()
    ];
});
