<?php

namespace App\Models;

use App\Models\Classes;
use App\Models\Bookings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseArt extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course_art';

    protected $casts = [
        'classes_id' => 'integer',
        'course_time_duration' => 'integer',
        'exam' => 'boolean',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course_name',
        'fees',
        'course_time_duration',
        'art',
        'exam',
        'classes_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'classes_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'course_id', 'id');
    }
}
