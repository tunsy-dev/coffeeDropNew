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

        $request = collect($request);

        $request->each(function($quantity, $pod_type){
            $amountProduct1 = 0;
            $amountProduct2 = 0;
            $amountProduct3 = 0;
            if($pod_type == config('enums.product')['product 1']){
                switch($quantity){
                    // less than 50
                    case $quantity <= config('enums.quantity')['tier 1']:
                        $amountProduct1 = $quantity * config('enums.amount')['amount 1'];
                    break;
                    // 50-500
                    case $quantity <= config('enums.quantity')['tier 2']:
                        $amountProduct1 = $quantity * config('enums.amount')['amount 2'];
                    break;
                    // >500
                    case $quantity > config('enums.quantity')['tier 3']:
                        $amountProduct1 = $quantity * config('enums.amount')['amount 3'];
                    break;
                    default:
                    $amountProduct1 = 0;
                }
            }elseif( $pod_type == config('enums.product')['product 2']){
                switch($quantity){

                    case $quantity <= config('enums.quantity')['tier 1']:
                        $amountProduct2 = $quantity * config('enums.amount')['amount 4'];
                        // dd($amount, '1');
                    break;
                    case $quantity <= config('enums.quantity')['tier 2']:
                        // dd($quantity);
                        $amountProduct2 = $quantity * config('enums.amount')['amount 5'];
                        // dd($amount, '2');
                    break;
                    case $quantity > config('enums.quantity')['tier 3']:
                        $amountProduct2 = $quantity * config('enums.amount')['amount 6'];
                        // dd($amount, '3');
                    break;
                    default:
                        $amountProduct2 = 0;
                }
            }elseif( $pod_type == config('enums.product')['product 3']){
                switch($quantity){
                    case $quantity <= config('enums.quantity')['tier 1']:
                        $amountProduct3 = $quantity * config('enums.amount')['amount 7'];
                        // dd($amount, '1');
                    break;
                    case $quantity <= config('enums.quantity')['tier 2']:
                        $amountProduct3 = $quantity * config('enums.amount')['amount 8'];
                        // dd($amount, '2');
                    break;
                    case $quantity > config('enums.quantity')['tier 3']:
                        $amountProduct3 = $quantity * config('enums.amount')['amount 9'];
                        // dd($amount, '3');
                    break;
                    default:
                        $amountProduct3 = 0;
                }
            } else {
                dd('bad');
            }




            // switch($pod_type) {
            //     case config('enums.product')['product 1']:
            //         switch($quantity){
            //             // less than 50
            //             case $quantity <= config('enums.quantity')['tier 1']:
            //                 $amountProduct1 = $quantity * config('enums.amount')['amount 1'];
            //             break;
            //             // 50-500
            //             case $quantity <= config('enums.quantity')['tier 2']:
            //                 $amountProduct1 = $quantity * config('enums.amount')['amount 2'];
            //             break;
            //             // >500
            //             case $quantity > config('enums.quantity')['tier 3']:
            //                 $amountProduct1 = $quantity * config('enums.amount')['amount 3'];
            //             break;
            //             default:
            //             $amountProduct1 = 0;
            //         }
            //         break;
            //     case config('enums.product')['product 2']:

            //         switch($quantity){

            //             case $quantity <= config('enums.quantity')['tier 1']:
            //                 $amountProduct2 = $quantity * config('enums.amount')['amount 4'];
            //                 // dd($amount, '1');
            //             break;
            //             case $quantity <= config('enums.quantity')['tier 2']:
            //                 // dd($quantity);
            //                 $amountProduct2 = $quantity * config('enums.amount')['amount 5'];
            //                 // dd($amount, '2');
            //             break;
            //             case $quantity > config('enums.quantity')['tier 3']:
            //                 $amountProduct2 = $quantity * config('enums.amount')['amount 6'];
            //                 // dd($amount, '3');
            //             break;
            //             default:
            //                 $amountProduct2 = 0;
            //         }
            //         break;
            //     case config('enums.product')['product 3']:
            //         // dd($pod_type, 'lun');
            //         switch($quantity){
            //             case $quantity <= config('enums.quantity')['tier 1']:
            //                 $amountProduct3 = $quantity * config('enums.amount')['amount 7'];
            //                 // dd($amount, '1');
            //             break;
            //             case $quantity <= config('enums.quantity')['tier 2']:
            //                 $amountProduct3 = $quantity * config('enums.amount')['amount 8'];
            //                 // dd($amount, '2');
            //             break;
            //             case $quantity > config('enums.quantity')['tier 3']:
            //                 $amountProduct3 = $quantity * config('enums.amount')['amount 9'];
            //                 // dd($amount, '3');
            //             break;
            //             default:
            //                 $amountProduct3 = 0;
            //         }

            dd($amountProduct1, $amountProduct2, $amountProduct3);

            $total = ($amountProduct1 + $amountProduct2 + $amountProduct3)/100 ;
            // dd($total);
            dd('£'.$total);
            dd($amountProduct1, $amountProduct2, $amountProduct3);
        });


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
