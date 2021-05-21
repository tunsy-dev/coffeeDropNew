<?php

namespace Database\Seeders;

use App\Models\CashbackRule;
use App\Models\Location;
use App\Models\OpeningTime;
use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // creat rules for cash back
        $cashback = new CashbackRule();
        // $cashback->quantity = config('enums.quantity')[0],
        $cashback->quantity = 'tier 1';
        $cashback->product_1 = 'amount 1';
        $cashback->product_2 = 'amount 2';
        $cashback->product_3 = 'amount 3';
        $cashback->save();

        $cashback = new CashbackRule();
        // $cashback->quantity = config('enums.quantity')[0],
        $cashback->quantity = 'tier 2';
        $cashback->product_1 = 'amount 4';
        $cashback->product_2 = 'amount 5';
        $cashback->product_3 = 'amount 6';
        $cashback->save();

        $cashback = new CashbackRule();
        // $cashback->quantity = config('enums.quantity')[0],
        $cashback->quantity = 'tier 3';
        $cashback->product_1 = 'amount 7';
        $cashback->product_2 = 'amount 8';
        $cashback->product_3 = 'amount 9';
        $cashback->save();

        // create a user
        $user = new User();
        $user->name = 'User one';
        $user->email = 'user@email.co.uk';
        $user->password = bcrypt('password');
        $user->user_type = 1;
        $user->save();

        // seed libaries
        $csv = Reader::createFromPath(storage_path('app/location_data.csv'));
        $csv->setHeaderOffset(0);
        $header = $csv->getHeader(); //returns the CSV header record
        $records = $csv->getRecords(); //returns all the CSV records as an Iterator object

        $postcodes = collect($records)->pluck('postcode')->toArray(); //returns the CSV document as a string
        try{
            $response = Http::post('api.postcodes.io/postcodes', ['postcodes' => $postcodes ]);
        }
        catch( Exception $e ){
            return $e->getMessage();
        }
        collect($response->json()['result'])->pluck('result')->each(function($item) {
            $location = new Location();
            $location->postcode = $item['postcode'];
            $location->lng = $item['longitude'];
            $location->lat = $item['latitude'];
            $location->city = $item['parliamentary_constituency'];
            $location->save();
        });

        // make a collection of of times for each location keyed by the postcode
        $times =  collect($records)->recursive()->keyBy('postcode');
        // Get the OpeingTimes and change the keys to the interger values
        $openingTimes = $times->map(function($item, $key ) {
            $item->forget('postcode');
            $openingTimes = $item->except(['closed_Monday', 'closed_Tuesday', 'closed_Wednesday', 'closed_Thursday', 'closed_Friday', 'closed_Saturday', 'closed_Sunday']);
            return $openingTimes->values();
        });
        // Get the closingTimes and change the keys to the interger values
        $closingTimes = $times->map(function($item, $key ) {
            $item->forget('postcode');
            $closingTimes = $item->except(['open_Monday', 'open_Tuesday', 'open_Wednesday', 'open_Thursday', 'open_Friday', 'open_Saturday', 'open_Sunday']);
            return $closingTimes->values();
        });
        // for each location Map through opening times returning a collection of opening times.
        // use the key from opening times to make an array of corresponding closing times for each day.
        $locationOpenings = collect($openingTimes)->map(function($item, $key) use ($closingTimes){
            return[ $item, $closingTimes[$key]];
        });
        // for each location get the openings, closings and location Id from our location model using the postcode
        collect($locationOpenings)->each(function($item, $postcode) {
            $openings = $item[0];
            $closings = $item[1];
            $location = Location::where('postcode', '=', $postcode)->first();
            $location_id = $location->id;
            // dd($location_id);
            // go through the openings for each location
            // create a new OpeningTime model to save to the database
            // save each opening time and the closing time closings time that relate to the opening time key
            // save the weekday from the $key
            // pass in the location_id vaiable and the save this to the database
            collect($openings)->each(function($opening, $key) use ($closings, $location_id){
                $opening_times = new OpeningTime();
                $opening_times->opening_time = $opening;
                $opening_times->closing_time = $closings[$key];
                $opening_times->weekday = $key;
                $opening_times->location_id = $location_id;
                $opening_times->save();
            });
        });
    }
}
