<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\UserItem;
use App\Services\ResponseService;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function index()
    {
        $items = FoodItem::where("id", ">", 0)->orderBy("name")->get();
        return ResponseService::SuccessResponse(["items" => $items], "Items List retrieved successfully");
    }


    public function store(Request $request)
    {
        $request->validate([
            "items" => "required|array",
            "items.*.id" => "required|numeric",
            "items.*.name" => "required|string",
            "items.*.category" => "required|string"
        ]);


        $user = $request->user();

        $dataToInsert = [];

        foreach ($request->items as $item) {

            $dataToInsert = [
                "name" => $item["name"],
                "category" => $item["category"],
                "user_id" => $user->id,
                "item_id" => $item["id"]
            ];

            $existingItem = UserItem::where("user_id", $user->id)->where("item_id", $item["id"])->first();

            if ($existingItem) {

                $existingItem->update(["item" => $item["name"], "category" => $item["category"]]);

            } else {

                UserItem::create($dataToInsert);
            }

        }


        $userItems = UserItem::where("user_id", $user->id)->get();


        return ResponseService::SuccessResponse($userItems, "Items added successfully");

    }


    public function userItems()
    {

        $userItems = UserItem::where("user_id", auth()->id())->get();

        return ResponseService::SuccessResponse($userItems, "Items retrieved successfully");

    }

    public function destroy(int $id)
    {
        $user = auth()->user();

        $item = UserItem::where("id", $id)->where("user_id", $user->id)->firstOrFail();

        $item->delete();

        $userItems = UserItem::where("user_id", $user->id)->get();

        return ResponseService::SuccessResponse($userItems, "Item deleted successfully");

    }


}
