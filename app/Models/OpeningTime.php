<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpeningTime extends Model
{
    use HasFactory;

    public function openingTimes () {
        return $this->belongsTo(Location::class);
    }

    public function getIsOpenAttribute() {
        return $this->opening_time !== $this->closing_time;
    }


}
