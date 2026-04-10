<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ComnyDetelesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
   

        return $this->collection->transform(function ($resource) use ($request) {
            return (new ComnyDetelesResourse($resource))->toArray($request);
        })->toArray();
    }
}
