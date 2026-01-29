<?php

namespace app\Services;

use App\Models\FoodItem;
use App\Models\Recipe;
use Carbon\Carbon;

class Utility
{

    public static function updateItems()
    {
        $items = json_decode(file_get_contents(__DIR__ . "/food_with_emoji.json"), true);

        foreach ($items["items"] as $item) {

            FoodItem::query()->where("name", $item["name"])->
            update(["image" => $item["image"], "image_type" => $item["image_type"]]);
        }


    }


    /**
     * Get the prompt for generating the image of dish
     * @param string $name name of the food
     * @return string prompt with food name
     */
    public static function makeImagePrompt(string $name): string
    {

        return "Ultra-realistic professional food photography of $name, freshly prepared and visually appetizing.The dish is plated beautifully on a clean, modern plate, with highly detailed textures, realistic moisture, steam where appropriate, and accurate, natural colors. Shot using a high-end DSLR camera with a shallow depth of field (f/2.8), sharp focus on the food, and cinematic natural lighting.Scene is set in a realistic, modern kitchen environment: a softly blurred kitchen background with countertops, cabinets, and subtle kitchen elements (cutting boards, herbs, utensils) visible but not distracting. Background lighting is warm and natural, as if coming from a nearby window, creating soft highlights and gentle shadows.The food is placed in the foreground on a kitchen counter, styled professionally for commercial food photography. No people, no text, no logos.Style: hyper-realistic, photorealistic, 8K resolution, studio-quality food photography, natural color grading, editorial food magazine style.";

    }


    public static function getLimit(int $userId): int
    {
        $todaysRecipesCount = Recipe::where("user_id", $userId)
            ->whereDate("created_at", Carbon::now()->toDateString())->count();

        $maxRequestest = 4;

        $limit = ceil($todaysRecipesCount / 4);

        return $maxRequestest - $limit;
    }

}
