<?php

namespace App\Http\Resources;

use App\Http\Resources\BookingResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookingCollection extends ResourceCollection
{

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'success'=>true,
            'data' => BookingResource::collection($this->collection),
        ];
    }
}
