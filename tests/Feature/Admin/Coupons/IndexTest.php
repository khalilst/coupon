<?php

namespace Tests\Feature\Admin\Coupons;

use App\Http\Resources\Admin\CouponResource;
use App\Models\Coupon;
use Tests\CouponTestCase;

/*
|--------------------------------------------------------------------------
| API Address: /admin/coupons
|--------------------------------------------------------------------------
| 1. Test Accuracy and Structure
| 2. Test Pagination
| 3. Test Filtering
| 4. Test Ordering
*/

class IndexTest extends CouponTestCase
{
    /**
     * The API's Address
     *
     * @const
     */
    protected const ADDR = '/api/admin/coupons';

    /**
     * Test the API's accuracy and result structure
     *
     * @return void
     */
    public function testStructureAccuracy()
    {
        $this->get(static::ADDR)
            ->assertOk()
            ->assertJson(['status' => true])
            ->assertJsonFragment(['name' => Coupon::active()->first()->name])
            ->assertJsonCount(config('coupon.page_size'), 'coupons')
            ->assertJsonStructure([
                'status',
                'coupons' => [
                    '*' => [
                        'id',
                        'name',
                        'link',
                        'amount',
                        'type',
                        'type_title',
                        'published_at',
                        'created_at',
                        'codes_count',
                        'users_count',
                        'brand' => ['id', 'name']
                    ]
                ]
            ]);
    }

    /**
     * Test index API supports pagination correctly
     *
     * @return void
     */
    public function testPagination()
    {
        $count = Coupon::active()->count();
        $pageSize = (int) config('coupon.page_size');
        $pageCount =  ceil($count / $pageSize);

        for ($page = 1; $page <= $pageCount; $page++) {
            $size = min($pageSize, $count - $pageSize * ($page - 1));

            $this->get(static::ADDR . "?page=$page")
                ->assertOk()
                ->assertJsonCount($size, 'coupons');
        }
    }

    /**
     * Test index API filtering.
     * Filtering is applied on all fields,
     * but we test name, amount, created_at, published_at with like, =, <= and >= operators respectively.
     *
     * @return void
     */
    public function testFiltering()
    {
        //Increase page size config to remove pagination and have all coupons at first page
        config()->set('coupon.page_size', Coupon::active()->count());

        //Choose a random active coupn
        $coupon = Coupon::active()->inRandomOrder()->first();

        //Partial json we expect to have in search result
        $jsonFragment = (new CouponResource($coupon))->toArray(null);

        //Filter by name (by LIKE operator)
        $name = substr($coupon->name, rand(1, 3));
        $this->get(static::ADDR . "?filters[name][like]=$name")
            ->assertOk()
            ->assertJsonFragment($jsonFragment);

        //Filter by amount (Exact Search)
        $this->get(static::ADDR . "?filters[amount][eq]={$coupon->amount}")
            ->assertOk()
            ->assertJsonFragment($jsonFragment);

        //Filter by created_at (before or less than or equal search)
        $this->get(static::ADDR . "?filters[created_at][max]={$coupon->created_at}")
            ->assertOk()
            ->assertJsonFragment($jsonFragment);

        //Filter by published_at (next or greater than or equal search)
        $this->get(static::ADDR . "?filters[published_at][min]={$coupon->published_at}")
            ->assertOk()
            ->assertJsonFragment($jsonFragment);
    }

    /**
     * Test index API ordering for all coupon fields.
     *
     * @return void
     */
    public function testOrdering()
    {
        //Change page size config to have only one coupon on each page
        config()->set('coupon.page_size', 1);

        $attributes = Coupon::first()->getAttributes();

        foreach ($attributes as $attribute => $value) {
            //Ascending Test
            $coupon = Coupon::active()->orderBy($attribute)->first();
            $this->doTestOrdering($coupon, $attribute);

            //Descending Test
            $coupon = Coupon::active()->orderBy($attribute, 'DESC')->first();
            $this->doTestOrdering($coupon, $attribute, 'DESC');
        }
    }

    /**
     * Perform ordering test for the given coupon
     *
     * @param  Coupon $coupon
     * @param  string $attribute
     * @param  string $direction [default = ASC]
     * @return void
     */
    protected function doTestOrdering($coupon, $attribute, $direction = 'ASC')
    {
            $jsonFragment = (new CouponResource($coupon))->toArray(null);

            $this->get(static::ADDR . "?orderings[$attribute]=$direction")
                ->assertOk()
                ->assertJsonFragment($jsonFragment);
    }
}
