<?php

namespace Tests\Cases;

use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminCouponTestCase extends CouponTestCase
{
    /**
     * The API's Address
     *
     * @const
     */
    protected const ADDR = '/api/admin/coupons';
}
