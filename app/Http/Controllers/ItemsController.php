<?php

namespace App\Http\Controllers;

use App\Jobs\MakeRecipeJob;
use App\Models\FoodItem;
use App\Models\Recipe;
use App\Models\UserItem;
use App\Services\ResponseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function view;

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

        $userItems = UserItem::with("item")->where("user_id", auth()->id())->get();
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
        $user = auth()->user();

        if ($user->timezone){
            $today = Carbon::now($user->timezone)->utc();
        }else{
            $today = Carbon::now();
        }

        $recipes = Recipe::where("user_id", $user->id)->whereDate("created_at", $today->toDateString())->get();
        return ResponseService::SuccessResponse($recipes, "Recipes retrieved successfully");

    }

    public function bookmark(int $id)
    {

        $user = auth()->user();


        $item = Recipe::where("id", $id)->where("user_id", $user->id)->firstOrFail();

        $item->update(["bookmarked" => !$item->bookmarked]);

    }

    public function recentBookmarks()
    {
        $bookmarks = Recipe::where("user_id", auth()->id())
            ->where("bookmarked", true)
            ->limit(5)
            ->orderBy("updated_at", "DESC")->get();
        return ResponseService::SuccessResponse($bookmarks, "Bookmarks retrieved successfully");

    }

    public function bookmarks()
    {
        $bookmarks = Recipe::where("user_id", auth()->id())
            ->where("bookmarked", true)->get();
        return ResponseService::SuccessResponse($bookmarks, "Bookmarks retrieved successfully");

    }

    public function show(string $id)
    {
        $recipe = Recipe::where("id", $id)->where('user_id', auth()->id())->firstOrFail();
        return ResponseService::SuccessResponse($recipe, "Recipe retrieved successfully");

    }


    public function publicPost(string $ulid)
    {
        $recipe = Recipe::with("user")->where("ulid", $ulid)->firstOrFail();


        return view("recipe", ["recipe" => $recipe]);


    }



    public function publicRecipe(string $ulid)
    {

        $recipe = Recipe::with("user")->where("ulid", $ulid)->firstOrFail();

        return ResponseService::SuccessResponse($recipe, "Recipe retrieved successfully");

    }


}
