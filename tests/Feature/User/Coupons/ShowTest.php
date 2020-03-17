<?php

namespace Tests\Feature\User\Coupons;

use App\Enums\ECodeType;
use App\Enums\ECouponType;
use App\Http\Resources\CouponResource;
use App\Models\Brand;
use App\Models\Code;
use App\Models\Coupon;
use App\Models\User;
use Tests\Cases\CouponTestCase;

class ShowTest extends CouponTestCase
{
    /**
     * Assert the coupon show API for normal users
     *
     * @param  int $codeType
     * @return Coupon
     */
    protected function assertCouponShow($codeType)
    {
        $coupon = factory(Coupon::class)->create([
            'expired_at' => null,
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'type' => ECouponType::DISCOUNT_CODE,
        ]);

        $code = factory(Code::class)->make(['type' => $codeType]);
        $code = $coupon->codes()->create($code->toArray());

        $user = factory(User::class)->create();

        $this->be($user);

        $this->json('get', static::ADDR . "/{$coupon->id}", [])
            ->assertOk()
            ->assertJson(OK + [ 'coupon' => (new CouponResource($coupon))->toArray(null)])
            ->assertJsonFragment(['code' => $code->value]);

        //Code assigned to the user
        $this->assertDatabaseHas('code_user', [
            'user_id' => $user->id,
            'code_id' => $code->id,
        ]);

        //Assert we'll see the same code at next visit
        $this->json('get', static::ADDR . "/{$coupon->id}", [])
            ->assertOk()
            ->assertJson(OK + [ 'coupon' => (new CouponResource($coupon))->toArray(null)])
            ->assertJsonFragment(['code' => $code->value]);

        return $coupon;
    }

    /**
     * Test the coupon show API.
     *
     * @return void
     */
    public function testCouponShowWithUniqueCodes()
    {
        $coupon = $this->assertCouponShow(ECodeType::UNIQUE);

        //Coupon should expired now
        $this->assertFalse($coupon->expired);
        $coupon->refresh();
        $this->assertTrue($coupon->expired);
    }

    /**
     * Test the coupon show API.
     *
     * @return void
     */
    public function testCouponShowWithNormalCodes()
    {
        $this->assertCouponShow(ECodeType::NORMAL);
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
