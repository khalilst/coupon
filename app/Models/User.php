<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Create a personal passport token
     *
     * @return string
     */
    public function createAccessToken()
    {
        return $this->createToken(config('app.name'))->accessToken;
    }

    /**
     * Remove all passport tokens
     *
     * @return void
     */
    public function logout()
    {
        $this->tokens()->delete();
    }

    /**
     * Return the user roles
     *
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Indicate the current user has the admin role.
     *
     * @return boolean
     */
    public function getIsAdminAttribute()
    {
        return $this->roles()->whereSlug('admin')->exists();
    }

    /**
     * Return the given coupon codes
     *
     * @return BelongsTo
     */
    public function codes()
    {
        return $this->belongsTo(Code::class);
    }
}
