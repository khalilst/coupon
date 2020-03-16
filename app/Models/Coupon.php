<?php

namespace App\Models;

use App\Enums\ECodeType;
use App\Enums\ECouponType;
use App\Traits\GeneralScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Coupon extends Model
{
    use GeneralScopes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'link', 'amount', 'type', 'published_at', 'expired_at'
    ];

    /**
     * Scope a query to only include active coupons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->notExpired()->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include non-active coupons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotActive($query)
    {
        return $query->expired()->orWhere('published_at', '>', now());
    }

    /**
     * Scope a query to only include expired coupons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expired_at')->where('expired_at', '<=', now());
    }

    /**
     * Scope a query to only include non-expired coupons.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotExpired($query)
    {
        return $query->whereNull('expired_at')->orWhere('expired_at', '>', now());
    }

    /**
     * Indicate the coupon is active or not.
     *
     * @return boolean
     */
    public function getIsActiveAttribute()
    {
        return !$this->expired && $this->published_at <= now();
    }

    /**
     * Indicate whether the coupon is expired or not.
     *
     * @return boolean
     */
    public function getExpiredAttribute()
    {
        return !is_null($this->expired_at) && $this->expired_at <= now();
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

    /**
     * Return the translation of the coupon type
     *
     * @return string
     */
    public function getTypeTitleAttribute()
    {
        return ECouponType::__((int) $this->type);
    }

    /**
     * Create a new coupon with respect to the given params
     *
     * @param  array $data
     * @return Coupon
     */
    public static function generate($data)
    {
        $brand = Brand::findOrFail($data['brand_id']);
        $coupon = $brand->coupons()->create($data);

        if (isset($data['code']) && $coupon->type == ECouponType::DISCOUNT_CODE) {
            $coupon->codes()->create([
                'value' => $data['code'],
                'type' => ECodeType::NORMAL,
            ]);
        }

        return $coupon;
    }

    /**
     * Update the coupon with the given params
     *
     * @param  array $data
     * @return this
     */
    public function modify($data)
    {
        //Update the brand
        if ($this->brand_id != $data['brand_id']) {
            $brand = Brand::findOrFail($data['brand_id']);
            $brand->coupons()->save($this);
        }

        //Update the codes if nobody used them before
        if (isset($data['code']) && !$this->usersCount) {
            $this->codes()->delete();

            $this->codes()->create(['value' => $data['code'], 'type' => ECodeType::NORMAL]);
        }

        //Update the coupon
        $this->update($data);

        return $this;
    }

    /**
     * Remove the coupon if nobody used it before.
     *
     * @return boolean
     */
    public function remove()
    {
        if ($this->usersCount) {
            return false;
        }

        return !!$this->delete();
    }

    /**
     * Return the coupon's user count cache key.
     *
     * @return int
     */
    public function getUsersCountCacheKeyAttribute()
    {
        return "coupon_user_count_{$this->id}";
    }

    /**
     * Return the users count who used this coupon.
     *
     * @return int
     */
    public function getUsersCountAttribute()
    {
        return cache()->rememberForever($this->usersCountCacheKey, function () {
            return $this->codes()
                ->join('code_user', 'code_user.code_id', '=', 'codes.id')
                ->count();
        });
    }

    /**
     * Increment the value of the coupon's user count in the cache.
     *
     * @param  int $value [default = 1]
     * @return int
     */
    public function incUsersCount($value = 1)
    {
        return cache()->has($this->usersCountCacheKey)
            ? cache()->increment($this->usersCountCacheKey, $value = 1)
            : $this->usersCount;
    }

    /**
     * Process the specified file to create/update coupon codes.
     *
     * @return this
     */
    public function processCodes()
    {
        $filename = $this->codesFilename;

        //We couldn't update codes when they are assigned to users
        if ($this->usersCount) {
            unlink($filename);
            return $this;
        }

        $limit = config('coupon.codes_create_many_limit');

        $file = fopen($filename, 'r');

        if ($file) {
            $this->codes()->delete();

            $list = [];
            while (($line = fgets($file)) !== false) {
                $list[] = [
                    'coupon_id' => $this->id,
                    'type' => ECodeType::UNIQUE,
                    'value' => trim($line),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($list) >= $limit) {
                    Code::insert($list);
                    $list = [];
                }
            }
            Code::insert($list);

            fclose($file);
            unlink($filename);
        }

        return $this;
    }

    /**
     * Return the coupon codes full path.
     *
     * @return string
     */
    public function getCodesFilenameAttribute()
    {
        return storage_path('codes') . "/{$this->id}";
    }


    /**
     * Assign a valid code to the given user.
     * If there was no more valid codes, the coupon expired automatically.
     *
     * @param  User   $user
     * @return Code
     */
    public function assignCode(User $user)
    {
        if ($this->expired) {
            return null;
        }

        return $this->assignNormalCode($user) ?: $this->assignUniqueCode($user);
    }

    /**
     * Assign a normal code to the given user
     *
     * @param  User   $user
     * @return Code
     */
    public function assignNormalCode(User $user)
    {
        $code = $this->codes()->where('type', ECodeType::NORMAL)->first();

        if ($code) {
            $code->users()->save($user);
        }

        return $code;
    }

    /**
     * Assign an unused unique code with respect to concurrent requests
     *
     * @param  User   $user
     * @return Code
     */
    public function assignUniqueCode(User $user)
    {
        $uniqeCodeQuery = $this->codes()
            ->doesntHave('users')
            ->selectRaw('MIN(id) as code_id, ?')
            ->groupBy('coupon_id')
            ->toSql();

        DB::insert("INSERT INTO code_user (code_id, user_id) $uniqeCodeQuery", [
            $user->id,
            $this->id,
        ]);

        $code = $this->getUserCode($user, false);
        if (!$code || !$this->remainingCodeCount) {
            $this->update(['expired_at' => now()]);
        }

        return $code;
    }

    /**
     * Return the remaining code count
     *
     * @return int
     */
    public function getRemainingCodeCountAttribute()
    {
        return $this->codes()->whereType(ECodeType::NORMAL)->count()
            + $this->codes()->whereType(ECodeType::UNIQUE)->doesntHave('users')->count();
    }

    /**
     * Return assigned code for the given user
     *
     * @param  User    $user [default = null]
     * @param  boolean $assignIfNotExists [defualt = true]
     * @return Code
     */
    public function getUserCode(User $user = null, $assignIfNotExists = true)
    {
        $user = $user ?? auth()->user();

        $code = $this->codes()
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();

        return $code ?? $this->assignCode($user);
    }
}
