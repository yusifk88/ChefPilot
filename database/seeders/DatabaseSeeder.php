<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

//        User::factory()->create([
//            'name' => 'Yusif katulie',
//            'email' => 'test@example.com',
//            "password" => Hash::make('password'),
//            "image_url" => "https://flobaze.atl1.cdn.digitaloceanspaces.com/public/avatar.webp"
//        ]);

        $this->callOnce(DefaultPhotoSeeder::class);

        if (app()->environment() !== 'production') {

            User::factory(1)->create();
            $this->call(DefaultPhotoSeeder::class);
            $this->call(FoodStuffSeeder::class);
        }
    }
}
