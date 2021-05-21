<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpeningTimeResource extends JsonResource
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
            // 'is_open' => $this->is_open ? "Open" : "Closed",
            // $this->where($this->opening_time, $this->closing_time),
            'day' => config('enums.day_of_the_week')[$this->weekday],
            $this->mergeWhen($this->is_open,[
                'open' => $this->opening_time,
                'closed' => $this->closing_time
            ]),
            $this->mergeWhen(!$this->is_open,[
                'open' => null,
                'closed' => null
            ]),

        ];
    }
}
