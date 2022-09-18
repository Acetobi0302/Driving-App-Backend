<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Franchise;
use App\Models\User;
use App\Models\Bookings;

class CarManagement extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'car_management';

    protected $casts = [
        'user_id' => 'integer',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'manufacturer',
        'model',
        'color',
        'number_plate',
        'art',
        'franchise_id',
        'user_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'car_id', 'id');
    }
}
