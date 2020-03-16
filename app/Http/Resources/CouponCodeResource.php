<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $couponResource = new CouponResource($this->resource);

        return $couponResource->toArray($request) + [
            'code' => optional($this->getUserCode())->value
        ];
    }
}
