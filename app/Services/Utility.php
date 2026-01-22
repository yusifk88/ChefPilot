<?php

namespace app\Services;

use App\Models\FoodItem;

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

}
