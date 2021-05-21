<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Http\Resources\OpeningTimesResource;
use App\Libraries\Library;
use App\Models\Location;
use App\Models\OpeningTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function index(Request $request){
        return LocationResource::collection(Location::paginate(5));
    }

    public function nearestLocation(Request $request){
        $postcode = $request->postcode;
        // get lat and long from postcode
        try{
            $response = Http::get("api.postcodes.io/postcodes/$postcode");
        }
        catch( Exception $e ){
            return $e->getMessage();
        }
        // dd($response->json()['result']);
        $result = $response->json()['result'];
        $lng = $result['longitude'];
        $lat = $result['latitude'];
        // dd($lng, $lat);
        $locationsWithDistance = Library::getLocationsWithDistance($lat, $lng);
        $closestLocation = $locationsWithDistance->sortBy('distance')->first();
        // $closestLocation->openingTimes;

        return new LocationResource($closestLocation);
        // return LocationResource::collection($closestLocation);
        // return true;
    }

    // create and save a new location and opening times
    public function store(Request $request)
    {
        $postcode = $request->postcode;
        try{
            $response = Http::get("api.postcodes.io/postcodes/$postcode");
        }
        catch( Exception $e ){
            return $e->getMessage();
        }
        // this is where it stops working
        $result = $response->json()['result'];
        $location = new Location();
        $location->lng = $result['longitude'];
        $location->lat = $result['latitude'];
        $location->postcode = $result['postcode'];
        $location->city = $result['parliamentary_constituency'];
        $location->save();
        // save opening times after location has been
        $location_id = $location->id;
        // dd($location_id);

        // saving openings
        // $defultOpeningTimes = [
        //     'monday' => null,
        //     'tuesday' => null,
        //     'wednesday' => null,
        //     'thursday' => null,
        //     'friday' => null,
        //     'saturday' => null,
        //     'sunday' => null
        // ];

        // $defultClosingTimes = [
        //     'monday' => null,
        //     'tuesday' => null,
        //     'wednesday' => null,
        //     'thursday' => null,
        //     'friday' => null,
        //     'saturday' => null,
        //     'sunday' => null
        // ];
        $defultOpeningTimes = [
            'monday' => 0,
            'tuesday' => 0,
            'wednesday' => 0,
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => 0
        ];

        $defultClosingTimes = [
            'monday' => 0,
            'tuesday' => 0,
            'wednesday' => 0,
            'thursday' => 0,
            'friday' => 0,
            'saturday' => 0,
            'sunday' => 0
        ];
        //  dd($defultOpeningTimes);
        $openings = collect($request->opening_times);
        $newOpenings = collect($defultOpeningTimes)->merge($openings);
        // dd($newOpenings);

        $closings = $request->closing_times;
        $newClosings = collect($defultClosingTimes)->merge($closings);
        $openings = $newOpenings;
        $closings = $newClosings;
        // dd($openings);
        // dd($closings);
        // $opening_times = new OpeningTime();
        $openings->each(function($opening, $key) use ($closings, $location_id){
            // dd(gettype($location_id));
          $day = (array_flip(config('enums.day_of_the_week'))[$key]);
            $opening_times = new OpeningTime();
            $opening_times->opening_time = $opening;
            // dd( $opening_times->opening_time);
            $opening_times->closing_time = $closings[$key];
            // dd($opening_times->closing_time);
            $opening_times->weekday = $day;
            // dd($opening_times->weekday);
            $opening_times->location_id = $location_id;
            // dd($opening_times->location_id);
            $opening_times->save();
            return true;
        });
}
}
