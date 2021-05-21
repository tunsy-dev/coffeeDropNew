<?php

namespace App\Libraries;

use App\Models\Location;
use App\Models\OpeningTime;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use JustSteveKing\LaravelPostcodes\Service\PostcodeService;
use League\Csv\Reader;
use Illuminate\Support\Str;

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
        // this is where it stops working
        $result = $response->json()['result'];
        $location = new Location();
        $location->lng = $result['longitude'];
        $location->lat = $result['latitude'];
        $location->postcode = $result['postcode'];
        $location->city = $result['parliamentary_constituency'];
        $location->save();

    }
    public static function getLocationsWithDistance ($lat, $lng) {
    // calculate nearest
    $location = Location::select('locations.*')
    // radius of earth 3966 miles
        ->selectRaw('( 3959 * acos( cos( radians(?) ) *
                           cos( radians( lat ) )
                           * cos( radians( lng ) - radians(?)
                           ) + sin( radians(?) ) *
                           sin( radians( lat ) ) )
                         ) AS distance', [$lat, $lng, $lat])
        // ->havingRaw("distance < ?", [$radius])
        ->get();

    return $location;
    }
}

