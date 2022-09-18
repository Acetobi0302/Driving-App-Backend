<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentBookings extends JsonResource
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
            'date' => $this->date,
            'start' => $this->start,
            'paid' => $this->paid,
            'franchise_name' => $this->franchise->name,
            'driver_name' => $this->driver->name,
            'course_name' =>  $this->course ? $this->course->course_name : null,
            'course_fees' => $this->course ? $this->course->fees : null,
            'course_time_duration' =>  $this->course ? $this->course->course_time_duration : null,
            'course_art' => $this->course ? $this->course->art : null,
            'amount' => $this->amount,
            'course_class' => $this->course ?  $this->course->classes->name : null,
        ];
    }
}
