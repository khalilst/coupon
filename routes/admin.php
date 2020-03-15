<?php

/*
|--------------------------------------------------------------------------
| Admin Coupon Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'coupons'], function () {
    Route::get('/', 'CouponController@index');
    Route::post('/', 'CouponController@store');
    Route::get('/{coupon}', 'CouponController@show');
    Route::patch('/{coupon}', 'CouponController@update');
    Route::delete('/{coupon}', 'CouponController@destroy');
});
