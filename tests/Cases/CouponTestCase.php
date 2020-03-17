<?php

namespace Tests\Cases;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTestCase extends TestCase
{
    use RefreshDatabase;

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

        $this->withoutMiddleware([
            \App\Http\Middleware\Admin::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\Authenticate::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\Auth\Middleware\Authorize::class,
        ]);
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

    /**
     * Return a random active coupon
     *
     * @return Coupon
     */
    public function randomCoupon()
    {
        return Coupon::active()->inRandomOrder()->first();
    }
}
