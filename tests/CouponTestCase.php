<?php

namespace Tests;

use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CouponTestCase extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->createFakeData();
    }

    /**
     * Create fake data for test
     *
     * @return void
     */
    protected function createFakeData()
    {
        $this->artisan('db:seed --class UsersTableSeeder');
        $this->artisan('db:seed --class BrandsTableSeeder');

        //Create active coupons more than coupon double page_size
        do {
            $this->artisan('db:seed --class CouponsTableSeeder');
        } while(Coupon::active()->count() < config('coupon.page_size') * 2);
    }
}
