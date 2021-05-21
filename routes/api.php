<?php

use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\AuthController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/locations', [LocationController::class, 'index']);
// get nearest location route
Route::get('/location/postcode', [LocationController::class, 'nearestLocation']);

// sanctum protected routes inside
Route::group(['middleware' => ['auth:sanctum']], function () {
    // logout
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('products/quote', [ProductController::class, 'store']);
    // post create new location route
    // Route::post('/locations', [LocationController::class, 'store']);
    // post calcualte cashback quote
    // Route::post('products/quote', [ProductController::class, 'index']);
});
Route::post('/locations', [LocationController::class, 'store']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// sanctum routes
Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});
