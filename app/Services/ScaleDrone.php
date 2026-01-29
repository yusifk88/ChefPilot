<?php

namespace app\Services;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScaleDrone
{

    public static function recipeCreated(User $user, Recipe $recipe)
    {
        $ID = "Yh4KOdyE8eyesTXu";

        $url = config("scale_drone.url") ."/". $ID . "/RecipeCreated_" . $user->id . "/publish";

        $response = Http::withToken(config("scale_drone.key"))->post($url, (array)$recipe);

        Log::info("scaledrone response", ["response" => $response->body()]);
        return $response->status();
    }

}
