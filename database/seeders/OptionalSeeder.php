<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\Classes;
use App\Models\Student;
use App\Models\CourseArt;
use App\Models\Franchise;
use App\Models\CarManagement;
use Illuminate\Database\Seeder;

class OptionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');
        $franchise = Franchise::first();

        $user = User::first();

        $cm = CarManagement::Create([
            "manufacturer" => "suzuki",
            "model" => "waganor",
            "color" => "White",
            "number_plate" => "AB036666",
            "art" => "test 2",
            "franchise_id" => $franchise->id,
            "user_id" => $user->id,
        ]);

        $classes = Classes::Create(['name'=>'class 1']);


        $ca = CourseArt::Create( [
            'course_name' => 'driving',
            'fees' => 2000,
            'course_time_duration' => 10,
            'art' => 'art',
            'classes_id' => $classes->id
        ]);


        $s = Student::Create([
            'sid' => 'WDWD458698',
            'first_name' => 'john',
            'last_name' => 'doe',
            'dob' => '1998-01-01',
            'franchise_id' => $franchise->id
        ]);


        $date= Carbon::today();
        $booking = Booking::Create([
            "driver_id"=>  $user->id,
            "student_id"=> $s->id,
            "course_id"=>  $ca->id,
            "note"=> "test note",
            "car_id"=> 1,
            "date"=> $date->format('Y-m-d'),
            "start"=> $date->format('Y-m-d H:i:s'),
            "end"=> $date->addMinutes(45)->format('Y-m-d H:i:s'),
            "paid"=> true,
            "franchise_id"=> $franchise->id
        ]);
       

    }
}
