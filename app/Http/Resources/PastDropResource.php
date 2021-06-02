<?php

namespace App\Http\Resources;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PastDropResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = User::where('id', $this->user_id)->first();
        if($user == null){
           $user = "unknown user";
        } else {
           $user = $user->name;
        };

        return [
            'name' => $user,
            'amount_cashback' => '£'.number_format($this->total/100,2),
            'products_and_quantities' => json_decode($this->products_and_quantities),
            'time' => Carbon::instance($this->created_at)->toDateTimeString(),
            'ip_address' => $this->ip_address,
        ];
    }
}
