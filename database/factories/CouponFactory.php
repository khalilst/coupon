<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\ECouponType;
use App\Models\Coupon;
use Faker\Generator as Faker;

$factory->define(Coupon::class, function (Faker $faker) {
    return [
        'name' => $faker->words(2, true),
        'link' => $faker->url(),
        'amount' => rand(10000, 1000000),
        'type' => ECouponType::randomValue(),
        'published_at' => now()->addHours(rand(-1000, 1000)),
        'expired_at' => now()->addHours(rand(-1000, 1000)),
    ];
});
