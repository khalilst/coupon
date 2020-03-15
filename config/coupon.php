<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Coupon Page size
    |--------------------------------------------------------------------------
    |
    | This value used for paginate method in coupon list (index) methods.
    |
    */

    'page_size' => env('COUPON_PAGE_SIZE', 25),

    /*
    |--------------------------------------------------------------------------
    | Coupon Codes Limit for createMany
    |--------------------------------------------------------------------------
    |
    | This value used to limit while creating codes by createMany method from
    | a text file.
    |
    */

   'codes_create_many_limit' => env('COUPON_CODES_CREATE_MANY_LIMIT', 1000),

];
