<?php

namespace Database\Seeders;

use App\Models\PriceTier;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ristretto = new Product();
        $ristretto->name = 'Ristretto';
        $ristretto->save();

        $espresso = new Product();
        $espresso->name = 'Espresso';
        $espresso->save();

        $lungo = new Product();
        $lungo->name = 'Lungo';
        $lungo->save();

        $pt1 = new PriceTier();
        $pt1->min = 0;
        $pt1->max = 50;
        $pt1->save();

        $pt2 = new PriceTier();
        $pt2->min = 51;
        $pt2->max = 500;
        $pt2->save();

        $pt3 = new PriceTier();
        $pt3->min = 501;
        $pt3->max = null;
        $pt3->save();

        $ristretto->priceTiers()->attach($pt1,['amount_pence'=>2]);
        $ristretto->priceTiers()->attach($pt2,['amount_pence'=>3]);
        $ristretto->priceTiers()->attach($pt3,['amount_pence'=>5]);

        $espresso->priceTiers()->attach($pt1,['amount_pence'=>4]);
        $espresso->priceTiers()->attach($pt2,['amount_pence'=>6]);
        $espresso->priceTiers()->attach($pt3,['amount_pence'=>10]);

        $lungo->priceTiers()->attach($pt1,['amount_pence'=>6]);
        $lungo->priceTiers()->attach($pt2,['amount_pence'=>9]);
        $lungo->priceTiers()->attach($pt3,['amount_pence'=>15]);
    }
}
