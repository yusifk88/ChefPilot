<?php

namespace app\Services;

use App\Models\Photo;
use App\Models\User;
use App\Models\UserItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AI
{
    private const SYSTEM_PROMPT = <<<'PROMPT'
You are an AI Recipe Assistant.

Your task is to analyze a JSON array of food inventory records provided by the user and suggest recipes that can be prepared using the available ingredients.

### Input
- You will receive a JSON array of ingredient records.
- Each record represents a food item the user currently has.
- You must only suggest recipes that can be made entirely or mostly with the provided ingredients.
- If an ingredient is missing but commonly available (e.g., salt, water, oil), you may assume it exists and note it as a "basic ingredient".

### Output Rules
- You MUST return a valid JSON array.
- DO NOT include any text outside of the JSON response.
- DO NOT include markdown, comments, or explanations.
- Ensure the JSON is properly formatted and parsable.

### Recipe Selection Rules
- Suggest exactly 4 recipes.
- Prioritize recipes that maximize usage of the provided ingredients.
- Avoid repeating recipes.
- Prefer simple, everyday meals unless ingredients suggest otherwise.
- Do not invent exotic ingredients.

### Recipe Object Structure
Each recipe object MUST strictly follow this schema

{
  "name": string,
  "description": string,
  "ingredients": [
    {
      "name": string,
      "quantity": string,
      "fromUserInventory": boolean,

    }
  ],
  ingredientMatchScore: number,
  "dietaryTags": string[],
  "instructions": [string,string,...],
  "difficulty": string,
  estimatedTimeMinutes: number,
  "nutrition": {
    "calories": string,
    "protein": string,
    "carbohydrates": string,
    "fat": string
  }
}

### Field Rules
- "name": Short, clear recipe name.
- "description": 1–2 sentences describing the dish.
- "ingredients":
  - Include all ingredients used.
  - Mark whether each ingredient comes from the user's inventory.
- "instructions":
  - Step-by-step cooking instructions.
  - Clear and concise.
- "nutrition":
  - Provide approximate nutritional values per serving.
  - Use common units (kcal, g).

### Constraints
- Do not include unsafe, inedible, or non-food items.
- Do not hallucinate unavailable ingredients beyond basic kitchen staples.
- Ensure cultural neutrality unless ingredients strongly imply a specific cuisine.

If no recipes can be made, return an empty JSON array [].
PROMPT;

    public static function MakeRecipe(int $userID): array
    {
        $food = UserItem::where('user_id', $userID)->select("name", "category")->get();

        $userPrompt = "Here is my food inventory for the week:" . $food->toJson() . "suggest some recipes for me";

        $model = "openai/gpt-oss-120b";

        $response = Http::withToken(config("openai.api_key"))
            ->post("https://api.groq.com/openai/v1/chat/completions", [
                "model" => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => self::SYSTEM_PROMPT,
                    ],
                    [
                        'role' => 'user',
                        'content' => $userPrompt,
                    ]
                ]

            ]);


        $object = $response->object();

        return json_decode($object->choices[0]->message->content);

    }


    public static function makeImageWithGermini(string $description,string $name)
    {
        $api_key = config("germini.api_key");
        $apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent?key=" . $api_key;

        $prompt = Utility::makeImagePrompt($description);

        $data = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $prompt]
                    ]
                ]
            ],
            "generationConfig" => [
                "responseModalities" => ["IMAGE"],
                "imageConfig" => [
                    "aspectRatio" => "1:1"
                ]
            ]
        ];

        $response = Http::post($apiUrl, $data);


       $res=  $response->object();


        if (isset($res->candidates[0]->content->parts[0]->inlineData))
        {

            $imageData= $res->candidates[0]->content->parts[0]->inlineData->data;

            $filename = "chefpilot/recipes/" . Str::uuid() . ".webp";

            $base64ImageData = base64_encode($imageData);
            $source = imagecreatefromstring($base64ImageData);

            $newWidth = 512;
            $newHeight = 512;
            $thumb = imagecreatetruecolor($newWidth, $newHeight);

            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, imagesx($source), imagesy($source));

            imagewebp($thumb, 'small_image.webp', 90);

            $compressedData = ob_get_clean();

            // Clean up
            imagedestroy($source);
            imagedestroy($thumb);


            Storage::disk('spaces')->put(
                $filename,
                $compressedData,
                'public'
            );

            $url= Storage::disk('spaces')->url($filename);

            return Photo::create([
                "url" => $url,
                "name"=>str_replace(" ","",$name),
            ]);

        }


        return Photo::where("name","default")->first();

    }


}
