<?php

namespace App\Models;

use App\Models\Franchise;
use App\Models\CarManagement;
use App\Models\CourseArt;
use App\Models\Student;
use App\Models\User;
use App\Models\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    protected $casts = [
        'driver_id' => 'integer',
        'student_id' => 'integer',
        'course_id' => 'integer',
        'car_id' => 'integer',
        'paid' => 'boolean',
        'user_id' => 'integer',
        'franchise_id' => 'integer',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'driver_id',
        'student_id',
        'course_id',
        'note',
        'car_id',
        'date',
        'start',
        'end',
        'paid',
        'paid_at',
        'amount',
        'user_id',
        'private',
        'franchise_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'date' => 'datetime:Y-m-d',
        'start' => 'datetime',
        'end' => 'datetime',
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id', 'id');
    }
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id', 'id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
    public function course()
    {
        return $this->belongsTo(CourseArt::class, 'course_id', 'id');
    }
    public function car()
    {
        return $this->belongsTo(CarManagement::class, 'car_id', 'id');
    }
    public function notes()
    {
        return $this->hasMany(Note::class, 'booking_id', 'id');
    }
    public function logs()
    {
        return $this->hasMany(Log::class, 'booking_id', 'id');
    }
    public function notecount()
    {
        return $this->hasMany(Note::class, 'booking_id', 'id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
