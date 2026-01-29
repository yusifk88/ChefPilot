<?php

namespace App\Jobs;

use App\Events\RecipeCreatedEvent;
use App\Mail\DailyRecipes;
use App\Models\Photo;
use App\Models\Recipe;
use App\Models\User;
use App\Notifications\RecipeCreated;
use app\Services\AI;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MakeRecipeJob implements ShouldQueue
{
    use Queueable;

    private int $userID;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userID)
    {
        $this->userID = $userID;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = AI::MakeRecipe($this->userID);

        $user = User::find($this->userID);

        $key = "foodRecipesTodayKey_".$user->id;
        Cache::forget($key);

        $recipes = [];

        $defaultPhoto = Photo::where("name","default")->first();


        // Recipe::where("user_id", $this->userID)->whereDate("created_at", date("Y-m-d"))->delete();

        foreach ($response as $recipe) {

            $recipeItem = Recipe::create([
                "user_id" => $this->userID,
                "name" => $recipe->name,
                "description" => $recipe->description,
                "ingredients" => $recipe->ingredients,
                "nutrition" => $recipe->nutrition,
                "images" =>$recipe?->images? implode(",", $recipe->images) : null,
                "difficulty" => $recipe->difficulty,
                "estimatedTimeMinutes" => $recipe->estimatedTimeMinutes,
                "tag" => implode(",", $recipe->dietaryTags),
                "instructions" => implode(",", $recipe->instructions),
                "ulid" => Str::ulid(),
                "photo_id" => $defaultPhoto?->id,

            ]);


            /**
             * generate an image for this food
             */

            MakeImage::dispatch($recipeItem)->delay(now()->addSeconds(5));


            $recipes[] = $recipeItem;


        }

        if (count($recipes) > 0) {

            Mail::to($user)->queue(new DailyRecipes(collect($recipes), $user))->delay(now()->addMinutes(3));

            broadcast(new RecipeCreatedEvent($user));
        }


    }
}
