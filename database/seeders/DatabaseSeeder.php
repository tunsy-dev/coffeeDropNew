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
//        $this->call(UserAndVenueSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
