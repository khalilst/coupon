<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\ECodeType;
use App\Models\Code;
use Faker\Generator as Faker;

$factory->define(Code::class, function (Faker $faker) {
    return [
        'code' => randomStr(),
        'type' => ECodeType::randomValue(),
    ];
});
