<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'location' => $this->city,
            'address' => [
                'city' => $this->city,
                'postcode' => $this->postcode,
            ],
            'coordinates' => [
                'latitude' => round($this->lat , 2),
                'longitude' => round($this->lng, 2),
            ],
            'distance' => round($this->distance, 2).' Miles',
            'openings' => OpeningTimeResource::collection($this->openingTimes)
        ];
    }
}
