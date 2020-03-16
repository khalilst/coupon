<?php

namespace App\Enums;

use MyCLabs\Enum\Enum;

class BasicEnum extends Enum
{
    /**
     * Return a random enum
     *
     * @return Enum
     */
    public static function random()
    {
        $key = array_rand(static::values());
        return static::$key();
    }

    /**
     * Return a random value
     *
     * @return mixed
     */
    public static function randomValue()
    {
        return static::random()->getValue();
    }

    /**
     * Return the translation of the given value
     *
     * @param  mixed $value
     * @return string
     */
    public static function __($value)
    {
        $class = strtolower(substr(class_basename(static::class), 1));
        $key = strtolower(static::search($value));

        return __("enums.$class.$key");
    }
}
