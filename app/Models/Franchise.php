<?php

namespace App\Models;

use App\Models\CarManagement;
use App\Models\Student;
use App\Models\User;
use App\Models\Bookings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Franchise extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'franchise';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'address',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'franchise_id', 'id');
    }

    public function cars()
    {
        return $this->hasMany(CarManagement::class, 'franchise_id', 'id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'franchise_id', 'id');
    }

    public function bookings()
    {
        return $this->hasMany(Bookings::class, 'franchise_id', 'id');
    }
}
