<?php

namespace Tests\Feature\Admin\Coupons;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Cases\AdminCouponTestCase;
use Tests\TestCase;

class DeleteTest extends AdminCouponTestCase
{
    /**
     * Test coupon delete API.
     *
     * @return void
     */
    public function testCouponDelete()
    {
        $coupon = $this->randomCoupon();
        $this->json('DELETE', static::ADDR . "/{$coupon->id}", [])
            ->assertOk()
            ->assertJson(OK);
    }

    /**
     * Test coupon delete validation.
     * Coupons with users couldn't be deleted.
     *
     * @return void
     */
    public function testCouponDeleteValidation()
    {
        $invalidId = Coupon::max('id') + 1;

        $this->json('DELETE', static::ADDR . "/{$invalidId}", [])->assertNotFound();

        $coupon = Coupon::active()->has('codes')->first();
        $coupon->codes()->first()->users()->save(User::inRandomOrder()->first());

        $this->json('DELETE', static::ADDR . "/{$coupon->id}", [])->assertStatus(422);
    }
}
