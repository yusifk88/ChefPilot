<?php

namespace App\Http\Controllers;

use App\Jobs\MakeRecipeJob;
use App\Models\FoodItem;
use App\Models\Recipe;
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

        MakeRecipeJob::dispatch($user->id);

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


    public function recipesToday()
    {
        $user = auth()->user();;
        $recipes = Recipe::where("user_id", $user->id)->whereDate("created_at", date("Y-m-d"))->get();
        return ResponseService::SuccessResponse($recipes, "Recipes retrieved successfully");

    }

    public function bookmark(int $id)
    {

        $user = auth()->user();


        $item =Recipe::where("id", $id)->where("user_id", $user->id)->firstOrFail();

        $item->update(["bookmarked" => !$item->bookmarked]);

    }

    public function recentBookmarks()
    {
     $bookmarks = Recipe::where("user_id", auth()->id())
         ->where("bookmarked",true)
         ->limit(5)
         ->orderBy("updated_at","DESC")->get();
     return ResponseService::SuccessResponse($bookmarks,"Bookmarks retrieved successfully");

    }

    public function bookmarks()
    {
     $bookmarks = Recipe::where("user_id", auth()->id())
         ->where("bookmarked",true)->get();
     return ResponseService::SuccessResponse($bookmarks,"Bookmarks retrieved successfully");

    }


}
