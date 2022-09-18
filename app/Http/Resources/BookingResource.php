<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'note' => $this->note,
            'notecount' => $this->notecount ? $this->notecount->count() : null,
            'date' => $this->date,
            'start' => $this->start,
            'exam' => $this->course ? $this->course->exam : false,
            'end' => $this->end,
            'paid' => $this->paid,
            'amount' => $this->amount,
            'color' => $this->course ?  ($this->course->exam ? '#3788d8' : ($this->paid ? 'green' : 'red')) : 'gray',

            'car_id' => $this->car ? $this->car_id : null,
            'number_plate' =>  $this->car ? $this->car->number_plate : null,

            'franchise_id' => $this->franchise_id,
            'franchise_name' => $this->franchise->name,

            'driver_id' => $this->driver_id,
            'driver_name' => $this->driver->name,

            'student_id' => $this->student ? $this->student_id : null,
            'student_name' => $this->student ? $this->student->first_name . ' ' . $this->student->last_name : null,
            'title' => $this->student ? $this->student->first_name . ' ' . $this->student->last_name : $this->driver->name,

            'course_id' => $this->course ? $this->course_id : null,
            'course_name' => $this->course ? $this->course->course_name : null,
            'course_fees' => $this->course ? $this->course->fees : null,
            'course_time_duration' => $this->course ?  $this->course->course_time_duration : null,
            'course_art' => $this->course ? $this->course->art : null,

            'class_id' => $this->course ? $this->course->classes_id : null,
            'course_class' => $this->course ?  $this->course->classes->name : null,
            'private' => $this->private,
            'paid_at' => $this->paid_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
