<?php

namespace App\Jobs;

use App\Models\Photo;
use App\Models\Recipe;
use app\Services\Utility;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MakeImage implements ShouldQueue
{
    use Queueable;

    private Recipe $recipe;

    /**
     * Create a new job instance.
     */
    public function __construct(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        $trimmedName = str_replace(" ", "", $this->recipe->name);

        $existingImage = Photo::where("name", $trimmedName)->first();

        if ($existingImage) {

            $this->recipe->update(["photo_id" => $existingImage->id]);


        } else {

            $prompt = Utility::makeImagePrompt($this->recipe->name);

            $response = Http::withHeaders([
                "content-type" => "application/json",
                "Ocp-Apim-Subscription-Key" => config("pixazo.api_key")
            ])->post(config("pixazo.api_url"), [
                "prompt" => $prompt,
                "num_steps" => 8,
                "seed" => 60,
                "height" => 512,
                "width" => 512,
            ]);


            if ($response->successful()) {

                $newPhoto = Photo::create([
                    "name" => $trimmedName,
                    "url" => $response->object()->output
                ]);

                $this->recipe->update(["photo_id" => $newPhoto->id]);

            }

        }
    }
}
