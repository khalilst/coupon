<?php

namespace App\Http\Controllers;

use App\Http\Resources\CouponCodeResource;
use App\Http\Resources\CouponResource;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::active()
            ->filter($request->filters ?? [])
            ->order($request->orderings ?? [])
            ->paginate(config('coupon.page_size'));

        return json(OK + ['coupons' => CouponResource::collection($coupons)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon)
    {
        return json(OK + ['coupon' => new CouponCodeResource($coupon)]);
    }
}
