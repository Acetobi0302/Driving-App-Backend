<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bookings;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Franchise;

class Student extends Model
{
    use SoftDeletes;

    protected $table = 'students';

    protected $casts = [
        'franchise_id' => 'integer'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sid',
        'first_name',
        'last_name',
        'franchise_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    public function franchise()
    {
        return $this->belongsTo(Franchise::class, 'franchise_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'student_id', 'id');
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
