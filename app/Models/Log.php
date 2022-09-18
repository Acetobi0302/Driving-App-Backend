<?php

namespace App\Models;

use App\Models\CarManagement;
use App\Models\Booking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'log';

    protected $casts = [
        'booking_id' => 'integer',
        'car_id' => 'integer',
        'user_id' => 'integer',
        'exam' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'booking_id',
        'change_date',
        'car_id',
        'date',
        'start',
        'end',
        'amount',
        'user_id',
        'type',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'date' => 'datetime:Y-m-d',
        'change_date' => 'datetime',
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }
    public function car()
    {
        return $this->belongsTo(CarManagement::class, 'car_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
