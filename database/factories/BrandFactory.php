<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Brand;
use Faker\Generator as Faker;

$factory->define(Brand::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'description' => $faker->text(),
        'website' => $faker->domainName(),
    ];
});
