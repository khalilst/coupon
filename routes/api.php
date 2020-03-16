<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::group(['namespace' => 'Auth'], function ($route) {
    $route->post('/register', 'RegisterController@register');

    $route->post('/login', 'LoginController@login');

    $route->post('/logout', 'LoginController@logout')->middleware('auth:api');
});


/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth:api'], function ($route) {
    $route->get('/me', 'UserController@getMe');
});

/*
|--------------------------------------------------------------------------
| Coupon Routes
|--------------------------------------------------------------------------
*/

Route::get('/coupons', 'CouponController@index');
Route::get('/coupons/{coupon}', 'CouponController@show')->middleware('auth:api');
