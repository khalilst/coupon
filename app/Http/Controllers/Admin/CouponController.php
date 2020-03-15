<?php

namespace App\Http\Controllers\Admin;

use App\Events\CodesUploaded;
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
     * @param  CouponRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CouponRequest $request)
    {
        $coupon = Coupon::generate($request->all());

        $this->processCodes($request, $coupon);

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
     * @param  CouponRequest  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $hasUser = $coupon->usersCount;

        $coupon = $coupon->modify($request->all());

        $this->processCodes($request, $coupon);

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

    /**
     * Process the codes file
     *
     * @param  Request $request
     * @param  Coupon  $coupon
     * @return void
     */
    public function processCodes(Request $request, Coupon $coupon)
    {
        if ($request->has('codes')) {
            $request->file('codes')->storeAs('/', $coupon->id, ['disk' => 'codes']);
            event(new CodesUploaded($coupon));
        }
    }
}
