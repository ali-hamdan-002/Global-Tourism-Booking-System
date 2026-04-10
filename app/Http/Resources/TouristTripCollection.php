<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;







class TouristTripCollection extends ResourceCollection
{
    // //**
    // * Transform the resource collection into an array.
    // *
    // * @return array<int|string, mixed>
    // */
    public function toArray($request)
    {
        return $this->collection->transform(function ($resource) use ($request) {
            return (new TouristTripResource($resource))->toArray($request);
        })->toArray();
    }
}
