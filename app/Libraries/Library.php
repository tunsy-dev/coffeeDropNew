<?php

namespace App\Libraries;

use App\Models\Location;
use Exception;
use Illuminate\Support\Facades\Http;

class Library
{
    public static function openingclosingTimes($postcode) {
        // sets opening and closing times


    }

    public function getDetailsFromPostcode ($postcode) {
        try{
            $response = Http::get("api.postcodes.io/postcodes/$postcode");
        }
        catch( Exception $e ){
            return $e->getMessage();
        }
        $result = $response->json()['result'];
        $location = new Location();
        $location->lng = $result['longitude'];
        $location->lat = $result['latitude'];
        $location->postcode = $result['postcode'];
        $location->city = $result['parliamentary_constituency'];
        $location->save();

    }
    public static function getLocationsWithDistance ($lat, $lng) {
    // calculate nearest with Haversine Formula
    $location = Location::select('locations.*')
    // radius of earth 3959 miles
        ->selectRaw('( 3959 * acos( cos( radians(?) ) *
                           cos( radians( lat ) )
                           * cos( radians( lng ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( lat ) ) )
                         ) AS distance', [$lat, $lng, $lat])
        ->get();

    return $location;
    }
}

