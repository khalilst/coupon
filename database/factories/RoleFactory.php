<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    $name = $faker->words(2, true);

    return [
        'slug' => Str::slug($name),
        'name' => $name,
    ];
});
