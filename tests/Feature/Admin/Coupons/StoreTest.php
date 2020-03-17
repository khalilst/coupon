<?php

namespace Tests\Feature\Admin\Coupons;

use App\Enums\ECodeType;
use App\Enums\ECouponType;
use App\Models\Brand;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\CodesFileFactory;
use Tests\CouponTestCase;

class StoreTest extends CouponTestCase
{
    use StoreRequestTest;

    /**
     * The API's Address
     *
     * @const
     */
    protected const ADDR = '/api/admin/coupons';

    /**
     * Assert the coupon store API
     *
     * @param  Coupon $coupon
     * @param  Brand $brand
     * @param  array $data
     * @return Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertCouponStore($coupon, $brand, $data)
    {
        return $this->json('post', static::ADDR, $data)
            ->assertOk()
            ->assertJson([
                'status' => true,
                'coupon' => [
                    'name' => $coupon->name,
                    'link' => $coupon->link,
                    'amount' => $coupon->amount,
                    'type' => $coupon->type,
                    'published_at' => $coupon->published_at->format('Y-m-d H:i:s'),
                    'brand' => [
                        'id' => $brand->id,
                        'name' => $brand->name,
                    ],
                ],
            ]);
    }

    /**
     * Test the accuracy of coupon store API
     *
     * @return void
     */
    public function testCouponStore()
    {
        $brand = Brand::inRandomOrder()->first();
        $coupon = factory(Coupon::class)->make(['brand_id' => $brand->id]);
        $data = $coupon->toArray();

        $this->assertCouponStore($coupon, $brand, $data);
    }

    /**
     * Test the coupon store action with normal code
     *
     * @return void
     */
    public function testCouponStoreWithNormalCode()
    {
        $brand = Brand::inRandomOrder()->first();
        $coupon = factory(Coupon::class)->make([
            'brand_id' => $brand->id,
            'type' => ECouponType::DISCOUNT_CODE,
        ]);
        $code = randomStr();
        $data = $coupon->toArray() + compact('code');

        $this->assertCouponStore($coupon, $brand, $data);

        $this->assertDatabaseHas('codes', [
            'value' => $code,
            'type' => ECodeType::NORMAL,
        ]);
    }

    /**
     * Test the coupon store action with unique codes
     *
     * @return void
     */
    public function testCouponStoreWithUniqueCodes()
    {
        $brand = Brand::inRandomOrder()->first();
        $coupon = factory(Coupon::class)->make(['brand_id' => $brand->id]);

        //Create codes file
        $filename = randomStr();
        $count = rand(1000, 10000);
        Storage::fake();

        $codesFile = app(CodesFileFactory::class)->generate($filename, $count);

        $data = $coupon->toArray() + ['codes' => $codesFile];

        $this->assertCouponStore($coupon, $brand, $data);

        $this->assertEquals($count, Coupon::withCount('codes')->latest('id')->first()->codes_count);
    }
}
