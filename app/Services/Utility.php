<?php

namespace app\Services;

use App\Models\Attempt;
use App\Models\FoodItem;
use Carbon\Carbon;

class Utility
{

    const int RECIPE_GENERATION_ATTEMPT_LIMIT = 1;


    public static function captureAttempt(int $userId)
    {
        $existingAttempt = Attempt::where('user_id', $userId)
            ->whereDate("created_at", Carbon::now()->toDateString())
            ->first();

        if ($existingAttempt) {

            $existingAttempt->increment("count");

        } else {

            Attempt::query()->create([
                "user_id" => $userId,
                "count" => 1,
            ]);
        }

    }


    /**
     * check and see of this user can generate recipes for the day
     * @param int $userID
     * @return bool
     */
    public static function canGenerate(int $userID): bool
    {
        return self::availableAttempts($userID) > 0;

    }

    /**
     * get the current available attempt for the user to limit abuse
     * @param int $userID
     * @return int
     */
    public static function availableAttempts(int $userID): int
    {
        return self::maxLimit() - self::getLimit($userID);

    }

    /**
     * when subscription is introduced, this ca become variable
     * @return int
     */


    public static function maxLimit(): int
    {


        return self::RECIPE_GENERATION_ATTEMPT_LIMIT;

    }

    /**
     * get the available limit
     * @param int $userId
     * @return int
     */
    public static function getLimit(int $userId): int
    {
        $attempts = Attempt::query()->where("user_id", $userId)
            ->whereDate("created_at", Carbon::now()->toDateString())->first();

        return $attempts ? $attempts->count : 1;
    }

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

}
