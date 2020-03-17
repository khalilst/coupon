<?php

namespace Tests\Feature\Admin\Coupons;

use App\Models\Brand;
use App\Models\Coupon;

trait StoreRequestTest
{
    /**
     * Test validation errors for required fields, brand id and field formats
     *
     * @return void
     */
    public function testCouponRequestValidation()
    {
        $brand = Brand::inRandomOrder()->first();
        $coupon = factory(Coupon::class)->make(['brand_id' => $brand->id]);

        //Required Validation
        $requiredFields = ['brand_id', 'name', 'link', 'amount', 'type', 'published_at'];
        foreach ($requiredFields as $field) {
            $data = $coupon->toArray();
            unset($data[$field]);

            $this->doTestCouponRequestValidation($data, $field);
        }

        //Invalid Brand ID
        $data = factory(Coupon::class)->make(['brand_id' => 0])->toArray();
        $this->doTestCouponRequestValidation($data, 'brand_id');

        //Invalid formats
        $fields = ['link', 'amount', 'type', 'published_at', 'expired_at'];
        foreach ($fields as $field) {
            $data = $coupon->toArray();
            $data[$field] = randomStr();
            $this->doTestCouponRequestValidation($data, $field);
        }
    }

    /**
     * Test Coupon Store Request with respect to the given params
     *
     * @param  array $data
     * @param  string $field
     * @return void
     */
    protected function doTestCouponRequestValidation($data, $field)
    {
        $this->json('post', static::ADDR, $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors([$field]);
    }
}
