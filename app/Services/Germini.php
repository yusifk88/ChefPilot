<?php

namespace app\Services;

use Illuminate\Support\Facades\Http;

class Germini
{

    public static function makeImage(string $query)
    {

        $model  = 'gemini-2.5-flash-image';

        $apiKey = config("germini.api_key");

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $payload = [
            "contents" => [
                [
                    "parts" => [
                        ["text" => $query]
                    ]
                ]
            ]
        ];

        $response = Http::post($url, $payload);
        return $response->object();

    }

}
