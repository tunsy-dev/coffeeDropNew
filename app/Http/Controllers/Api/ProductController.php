<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PastDropResource;
use App\Models\PastDrop;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    public function index(Request $request){
        return PastDropResource::collection(PastDrop::orderBy('id', 'desc')->take(5)->get());
        // return PastDropResource::collection(PastDrop::paginate(5));
    }
    public function store(Request $request){
        // dd(Auth::user());
        // Gets all the items in the request  and turns it into a collection.
        // Reduce then reduces the collection into a single value this has been passed to start as 0.
        // The result of each iteration is passed into the subsequent iteration.
        $products = collect($request->all())->reduce(function ($carry, $item, $key){
            //    key is name, item is amount
            // setting the total calculated
            $totalCalculated = 0;
            // getting the product where the name matches the key passed in and returning the first element
            $thisProduct = Product::where('name', $key)->first();
            // if there is a product that meats the above criteria
            if($thisProduct !== null)
            {
                 //getting the total of current product
                $quantity = $item;
                // getting the price tiers for that product
                $tiers = $thisProduct->pricetiers;
                // sort tiers in order
                $tiers = $tiers->sort();
                // setting the total remaing quanity of that product from the request
                $totalRemaining = $quantity;
                // loop tiers in order passing in the actual value of totalRemaing and totalCalculate
                $tiers->each(function ($tier) use (&$totalRemaining, &$totalCalculated) {
                    // if the tier max is null or total remaining is less than or equal to tier max
                    if($tier->max == null || $totalRemaining <= $tier->max)
                    {
                        // caluclate the amount this is the total remaining * the amount at that price tier
                        $amount = $totalRemaining * $tier->pivot->amount_pence;
                        //  add this too the total calcualted
                        $totalCalculated = $totalCalculated + $amount;
                        return false;
                    }
                    // if the above criteria of the if isn't met this runs.
                    else {
                        // setting the qty in each of the tiers
                        if($tier->min != 0)
                        {
                            $qty = ($tier->max - ($tier->min - 1));
                        }
                        else{
                            $qty = $tier->max;
                        }
                        // calcualting the amount
                        $amount = $qty * $tier->pivot->amount_pence;
                        // seting too total calculated
                        $totalCalculated += $amount;
                        // setting the new total remining
                        $totalRemaining = $totalRemaining - $qty;
                    }
                });
            }
            // adds the total on to the carry,
            return $carry + $totalCalculated;
        },0);
        // dd(auth('sanctum')->user()['id']);
        $all = json_encode($request->all());
        $drops = new PastDrop();
        if (auth('sanctum')->user() == null) {
            $drops->user_id = null;
        } else {
            $drops->user_id = auth('sanctum')->user()['id'];
         }
        $drops->ip_address = $request->ip();
        $drops->products_and_quantities = $all;
        $drops->total = $products;
        $drops->save();

        // returns the final amount in Json format
        return Response::json([
            'data' => [
                'amount_pence' => $products,
                'amount_formatted' => '£'.number_format($products/100,2),
            ]
        ] );
    }
}
