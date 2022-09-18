<?php

namespace App\Http\Resources;

use App\Http\Resources\StudentBookings;
use Illuminate\Http\Resources\Json\ResourceCollection;

class StudentBookingCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success' => true,
            'data' => StudentBookings::collection($this->collection),
        ];
    }
}
