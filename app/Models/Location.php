<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public function coffeeDrops () {
        return $this->hasMany(CoffeeDrop::class);
    }
    public function openingTimes () {
        return $this->hasMany(OpeningTime::class);
    }
}
