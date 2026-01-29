<?php

namespace app\Services;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScaleDrone
{

    /**
     * @throws ConnectionException
     */
    public static function recipeCreated(User $user, Recipe $recipe)
    {
        $ID = "Yh4KOdyE8eyesTXu";

        $url = config("scale_drone.url") ."/". $ID . "/RecipeCreated_" . $user->id . "/publish";

        $key =config("scale_drone.key");

        $authToken = base64_encode("$ID:$key");

        $response = Http::withHeaders([
            "Authorization" => "Basic $authToken",
            "Content-Type" => "application/json"
        ])->post($url, (array)$recipe);

        Log::info("scaledrone response", ["response" => $response->body()]);
        return $response->status();
    }

}
