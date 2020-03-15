<?php

namespace App\Models;

use App\Enums\ECodeType;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 'type'
    ];

    /**
     * Return the owner coupon model
     *
     * @return BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Return the associated user(s).
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('created_at');
    }

    /**
     * Return the translation of the coupon type
     *
     * @return string
     */
    public function getTypeTitleAttribute()
    {
        return ECodeType::__($this->type);
    }
}
