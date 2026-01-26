<?php

namespace Database\Seeders;

use App\Models\Photo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Photo::create([
            "name"=>"default",
            "url"=>"https://flobaze.atl1.cdn.digitaloceanspaces.com/chefpilot/photos/placeholder.png"
        ]);
    }
}
