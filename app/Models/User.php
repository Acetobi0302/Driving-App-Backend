<?php

namespace App\Models;

use App\Models\Franchise;
use App\Models\CarManagement;
use App\Models\Bookings;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password', 'role', 'address', 'phone', 'franchise_id',
    ];

    protected $casts = [
        'franchise_id' => 'integer'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id', 'id');
    }

    public function cars()
    {
        return $this->hasMany(CarManagement::class, 'user_id', 'id');
    }

    public function driversBookings()
    {
        return $this->hasMany(Bookings::class, 'driver_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'user_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(CarManagement::class, 'user_id', 'id');
    }
}
