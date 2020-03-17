<?php

namespace Tests\Feature\Admin\Coupons;

use App\Http\Resources\Admin\CouponResource;
use App\Models\Coupon;
use Tests\Cases\AdminCouponTestCase;

class ShowTest extends AdminCouponTestCase
{
    /**
     * Test the coupon show API.
     *
     * @return void
     */
    public function testCouponShow()
    {
        $coupon = $this->randomCoupon();

        $this->json('get', static::ADDR . "/{$coupon->id}", [])
            ->assertOk()
            ->assertJson(OK + [ 'coupon' => (new CouponResource($coupon))->toArray(null)]);
    }

    /**
     * Test validation for the coupon show API.
     *
     * @return void
     */
    public function testCouponShowValidation()
    {
        $invalidId = Coupon::max('id') + 1;

        $this->json('get', static::ADDR . "/{$invalidId}", [])->assertNotFound();
    }
}
