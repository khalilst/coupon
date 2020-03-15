<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'website'
    ];

    /**
     * Return the associated coupons
     *
     * @return HasMany
     */
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
