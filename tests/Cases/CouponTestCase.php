<?php

namespace Tests\Cases;

use App\Models\Coupon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CouponTestCase extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * The API's Address
     *
     * @const
     */
    protected const ADDR = '/api/v1.0/coupons';

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
