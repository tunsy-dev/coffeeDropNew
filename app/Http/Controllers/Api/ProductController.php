<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoffeeDrop;
use App\Models\CoffeeDropProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request){

    }
    public function store(Request $request){

        // dd(Auth::user());
        // if product 1 quntity is
        $products = collect($request->all())->sum(function ($item,$key){
            $tot = 0;
            $thisProduct = Product::where('name',$key)->first();
            if($thisProduct !== null)
            {
                //if it's not null, let's do something
                $prices = $thisProduct->pricetiers;
                
            }
            return $tot;
        });
        dd($products);



        // $request->each(function($quantity, $type){
            // $coffeDrop = new CoffeeDrop();
            // needs user id so need to log in as user
            //  $product = new Product();
            //  $product->coffeeDrops()->attach($quantity);
            //  $product->name = $type;
            //  $product->save();
            //  $quanity = new
        // });
        // $product = new Product();
    }


}
