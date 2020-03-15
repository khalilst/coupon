<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->only(
            'id',
            'name',
            'link',
            'amount',
            'type',
            'type_title',
            'published_at'
        ) + [
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'codes_count' => $this->codes()->count(),
            'users_count' => $this->usersCount,
            'brand' => [
                'id' => $this->brand->id,
                'name' => $this->brand->name,
            ],
        ];
    }
}
