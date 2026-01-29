<?php

namespace App\Http\Controllers;

use App\Jobs\MakeRecipeJob;
use App\Models\FoodItem;
use App\Models\Recipe;
use App\Models\UserItem;
use App\Services\ResponseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use function tests\data;
use function view;

class ItemsController extends Controller
{
    public function index()
    {
        $items = Cache::remember("foodItemsCachekey", 60 * 120 * 24, function () {

            return FoodItem::where("id", ">", 0)->orderBy("name")->get();

        });


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


        $key = "foodItemsCachekey_".$user->id;
        Cache::forget($key);

        $userItems = UserItem::with("item")->where("user_id", $user->id)->get();

        return ResponseService::SuccessResponse($userItems, "Items added successfully");

    }


    public function userItems()
    {

        $key = "foodItemsCachekey_".auth()->id();

        $userItems = Cache::remember($key, 60 * 60 * 24, function () {

            return UserItem::with("item")->where("user_id", auth()->id())->get();

        });

        return ResponseService::SuccessResponse($userItems, "Items retrieved successfully");

    }

    public function destroy(int $id)
    {
        $user = auth()->user();

        $key = "foodItemsCachekey_".$user->id;
        Cache::forget($key);

        $item = UserItem::where("id", $id)->where("user_id", $user->id)->firstOrFail();

        $item->delete();


        $userItems = UserItem::where("user_id", $user->id)->get();

        return ResponseService::SuccessResponse($userItems, "Item deleted successfully");

    }


    public function recipesToday()
    {
        $user = auth()->user();

        if ($user->timezone) {
            $today = Carbon::now($user->timezone)->utc();
        } else {
            $today = Carbon::now();
        }

        $key = "foodRecipesTodayKey_".$user->id;

        $recipes = collect(Cache::remember($key, 60 * 60 * 24, function () use ($user,$today) {

            return Recipe::with("photos")->where("user_id", $user->id)->whereDate("created_at", $today->toDateString())->get();

        }));

        return ResponseService::SuccessResponse($recipes, "Recipes retrieved successfully");

    }

    public function bookmark(int $id)
    {

        $user = auth()->user();
        $key = "bookmarkedRecipesKey_".$user->id;

        Cache::forget($key);

        $item = Recipe::where("id", $id)->where("user_id", $user->id)->firstOrFail();

        $item->update(["bookmarked" => !$item->bookmarked]);

    }

    public function recentBookmarks()
    {
        $key = "bookmarkedRecipesKey_".auth()->id();

        $bookmarks =  Cache::remember($key, 60 * 60 * 24, function () {
            return Recipe::with("photos")->where("user_id", auth()->id())
                ->where("bookmarked", true)
                ->limit(5)
                ->orderBy("updated_at", "DESC")->get();
        });

        return ResponseService::SuccessResponse($bookmarks, "Bookmarks retrieved successfully");

    }

    public function bookmarks()
    {
        $key = "bookmarkedRecipesKey_".auth()->id();

        $bookmarks =  Cache::remember($key, 60 * 60 * 24, function () {
            return Recipe::with("photos")->where("user_id", auth()->id())
                ->where("bookmarked", true)->get();
        });

        return ResponseService::SuccessResponse($bookmarks, "Bookmarks retrieved successfully");

    }

    public function show(string $id)
    {
        $recipe = Recipe::with("photos")->where("id", $id)->where('user_id', auth()->id())->firstOrFail();
        return ResponseService::SuccessResponse($recipe, "Recipe retrieved successfully");

    }


    public function publicPost(string $ulid)
    {

        $recipe = Recipe::with("photos")->with("user")->where("ulid", $ulid)->firstOrFail();

        return view("recipe", ["recipe" => $recipe]);


    }


    public function publicRecipe(string $ulid)
    {

        $recipe = Recipe::with("photos")->with("user")->where("ulid", $ulid)->firstOrFail();

        return ResponseService::SuccessResponse($recipe, "Recipe retrieved successfully");

    }


    public function getDailyRequestCount()
    {
        $todaysRecipesCount = Recipe::where("user_id", auth()->id())
            ->whereDate("created_at",Carbon::now()->toDateString())->count();

        $limit = ceil($todaysRecipesCount/4);

        return ResponseService::SuccessResponse(data:["count"=>$limit],message: "Recipes request count retrieved successfully");

    }


    public function getRecipes()
    {

        $todaysRecipesCount = Recipe::where("user_id", auth()->id())
            ->whereDate("created_at",Carbon::now()->toDateString())->count();

        $user = auth()->user();

        $userItemsCount = UserItem::where("user_id", $user->id)->count();
        if ($userItemsCount===0){
            return ResponseService::FailedResponse(message: "Your food inventory is empty add your food to get personalised recipes");

        }

        if ($user->id!=2 and $todaysRecipesCount>=12) {

            return ResponseService::FailedResponse(message: "You have exceeded your daily limits for the day, try again tomorrow");
        }

        $key = "foodItemsCachekey_".$user->id;
        Cache::forget($key);

        MakeRecipeJob::dispatch($user->id);


    }


}
