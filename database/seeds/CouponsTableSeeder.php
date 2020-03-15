<?php

use App\Enums\ECodeType;
use App\Models\Brand;
use App\Models\Code;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Brand::inRandomOrder()
            ->take(5)
            ->get()
            ->each(function ($brand) {
                $coupons = factory(Coupon::class, rand(2, 10))->make();

                $brand->coupons()
                    ->createMany($coupons->toArray())
                    ->each(function ($coupon) {
                        $this->createCodes($coupon);
                    });

            });
    }

    /**
     * Create codes for the given coupon
     *
     * @param  Coupon $coupon
     * @return void
     */
    protected function createCodes($coupon)
    {
        if (rand(0, 1)) {
            $this->createNormalCode($coupon);
        } else {
            $this->createUniqueCodes($coupon);
        }
    }

    /**
     * Create a normal code for the given coupon
     *
     * @param  Coupon $coupon
     * @return void
     */
    protected function createNormalCode($coupon)
    {
        $code = factory(Code::class)->make(['type' => ECodeType::NORMAL]);

        $coupon->codes()->create($code->toArray());
    }

    /**
     * Create the unique codes for the given coupon
     *
     * @param  Coupon $coupon
     * @return void
     */
    protected function createUniqueCodes($coupon)
    {
        $code = factory(Code::class, rand(10, 100))->make(['type' => ECodeType::UNIQUE]);

        $coupon->codes()->createMany($code->toArray());
    }
}
