<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['first_name', 'last_name', 'phone_number', 'name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'pivot'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, UserRole::class)
            ->select(['roles.id', 'roles.name']);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    private function name()
    {
        return $this->getAttributeValue('first_name') . ' ' .
            $this->getAttributeValue('last_name');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->name(),
            'roles' => $this->roles->map((function ($role) {
                Arr::forget($role, 'pivot');
                return $role;
            }))
        ];
    }
}
