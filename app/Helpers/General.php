<?php

use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| General Constants
|--------------------------------------------------------------------------
*/

define('OK', ['status' => true]);
define('NOK', ['status' => false]); //Not OK result.

/*
|--------------------------------------------------------------------------
| General Helper Methods
|--------------------------------------------------------------------------
*/

/**
 * Return a random string
 *
 * @param  integer $length [default 16]
 * @return string
 */
function randomStr($length = 16)
{
    return Str::random($length);
}

/**
 * Return a json response
 *
 * @param  mixed $data
 * @param  int  $status
 * @return Illuminate\Http\JsonResponse
 */
function json($data, $status = 200)
{
    return response()->json($data, $status);
}
