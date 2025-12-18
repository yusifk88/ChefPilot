<?php

namespace App\Jobs;

use App\Models\Recipe;
use app\Services\AI;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

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

        Recipe::where("user_id", $this->userID)->whereDate("created_at", date("Y-m-d"))->delete();

        foreach ($response as $recipe) {

            Recipe::create([
                "user_id" => $this->userID,
                "name" => $recipe->name,
                "description" => $recipe->description,
                "ingredients" => $recipe->ingredients,
                "nutrition" => $recipe->nutrition,
                "images" => implode(",",$recipe->images),
                "difficulty" => $recipe->difficulty,
                "estimatedTimeMinutes" => $recipe->estimatedTimeMinutes,
                "tag"=>implode(",",$recipe->dietaryTags),
                "instructions"=>implode(",",$recipe->instructions),

            ]);
        }


    }
}
