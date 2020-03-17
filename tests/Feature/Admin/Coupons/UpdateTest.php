<?php

namespace Tests\Feature\Admin\Coupons;

use App\Enums\ECodeType;
use App\Enums\ECouponType;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\Cases\AdminCouponTestCase;
use Tests\CodesFileFactory;

class UpdateTest extends AdminCouponTestCase
{
    use RequestTest;

    /**
     * Assert the coupon update API
     *
     * @param  Coupon $coupon
     * @param  Brand $brand
     * @param  array $data
     * @return Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertCouponUpdate($coupon, $brand, $data)
    {
        $this->json('patch', static::ADDR . "/{$coupon->id}", $data)
            ->assertOk()
            ->assertJson([
                'status' => true,
                'coupon' => [
                    'id' => $coupon->id,
                    'name' => $data['name'],
                    'link' => $data['link'],
                    'amount' => $data['amount'],
                    'type' => $data['type'],
                    'published_at' => $data['published_at'],
                    'brand' => [
                        'id' => $brand->id,
                        'name' => $brand->name,
                    ],
                ],
            ]);
    }

    /**
     * Test the accuracy of coupon update API
     *
     * @return void
     */
    public function testCouponUpdate()
    {
        $brand = Brand::inRandomOrder()->first();
        $coupon = factory(Coupon::class)->make(['brand_id' => $brand->id]);
        $data = $coupon->toArray();

        $this->assertCouponUpdate($this->randomCoupon(), $brand, $data);
    }

    /**
     * Test the coupon update action with normal code
     *
     * @return void
     */
    public function testCouponUpdateWithNormalCode()
    {
        $brand = Brand::inRandomOrder()->first();
        $coupon = factory(Coupon::class)->make([
            'brand_id' => $brand->id,
            'type' => ECouponType::DISCOUNT_CODE,
        ]);
        $code = randomStr();
        $data = $coupon->toArray() + compact('code');

        $this->assertCouponUpdate($this->randomCoupon(), $brand, $data);

        $this->assertDatabaseHas('codes', [
            'value' => $code,
            'type' => ECodeType::NORMAL,
        ]);
    }

    /**
     * Test the coupon update action with unique codes
     *
     * @return void
     */
    public function testCouponUpdateWithUniqueCodes()
    {
        $brand = Brand::inRandomOrder()->first();
        $coupon = factory(Coupon::class)->make([
            'brand_id' => $brand->id,
            'type' => ECouponType::DISCOUNT_CODE,
        ]);

        //Create codes file
        $filename = randomStr();
        $count = rand(1000, 10000);
        Storage::fake();

        $codesFile = app(CodesFileFactory::class)->generate($filename, $count);

        $data = $coupon->toArray() + ['codes' => $codesFile];

        $randomCoupon = $this->randomCoupon();
        $this->assertCouponUpdate($randomCoupon, $brand, $data);

        $this->assertEquals($count, $randomCoupon->codes()->count());
    }
}
