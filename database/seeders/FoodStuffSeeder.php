<?php

namespace Database\Seeders;

use App\Models\FoodItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodStuffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = json_decode(file_get_contents(__DIR__ . "/raw_foods_global.json"), true);
        FoodItem::insert($items["items"]);
    }
}
