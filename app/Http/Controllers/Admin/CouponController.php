<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Http\Resources\Admin\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $coupons = Coupon::active()->paginate(config('coupon.page_size'));

        return json(OK + ['coupons' => CouponResource::collection($coupons)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CouponRequest $request)
    {
        $coupon = Coupon::generate($request->all());

        return json(OK + ['coupon' => new CouponResource($coupon)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Coupon $coupon)
    {
        return json(OK + ['coupon' => new CouponResource($coupon)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Coupon $coupon)
    {
        $hasUser = $coupon->usersCount;
        $coupon = $coupon->modify($request->all());

        $result = OK + ['coupon' => new CouponResource($coupon)];

        if ($hasUser) {
            $result += ['message' => __('messages.coupon.updated_with_warning')];
        }

        return json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Coupon $coupon)
    {
        $result = $coupon->remove()
            ? OK
            : NOK + ['message' => __('messages.coupon.delete_failed')];

        return json($result);
    }
}
