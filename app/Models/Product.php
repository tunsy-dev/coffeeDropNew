<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function coffeeDrops() {
        return $this->belongsToMany(coffeeDrops::class)
        ->withPivot(['quantity']);
    }

    public function priceTiers()
    {
        return $this->belongsToMany(PriceTier::class)->withPivot(['amount_pence']);
    }
}
