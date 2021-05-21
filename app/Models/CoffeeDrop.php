<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoffeeDrop extends Model
{
    use HasFactory;

    public function user () {
        return $this->belongsTo(User::class);
    }

    public function location () {
        return $this->hasOne(Location::class);
    }

    // the products that belong to this coffee pod drop
    public function products() {
        return $this->belongsToMany(Product::class)->withPivot(['quantity']);;
    }

}
