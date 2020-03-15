<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'link', 'amount', 'type'
    ];

    /**
     * Scope a query to only include active coupons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('published_at', '<=', now())->whereNull('expired_at');
    }

    /**
     * Scope a query to only include expired coupons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expired_at');
    }

    /**
     * Indicate the coupon is active or not.
     *
     * @return boolean
     */
    public function getIsActiveAttribute()
    {
        return is_null($this->expired_at) && $this->published_at <= now();
    }

    /**
     * Indicate the coupon is expired or not.
     *
     * @return boolean
     */
    public function getIsExpiredAttribute()
    {
        return !is_null($this->expired_at);
    }

    /**
     * Return the related brand
     *
     * @return BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Return the associated coupon codes
     *
     * @return HasMany
     */
    public function codes()
    {
        return $this->hasMany(Code::class);
    }
}
