<?php

namespace app\Services;

use Illuminate\Support\Facades\Http;
use Unsplash\HttpClient;

class Unsplash
{

    public static function init()
    {

        HttpClient::init([
            'applicationId' => config('unsplash.app_id'),
            'secret' => config('unsplash.app_secret'),
            'callbackUrl' => 'https://your-application.com/oauth/callback',
            'utmSource' => 'NAME OF YOUR APPLICATION'
        ]);
    }


    public static function search(string $query = "")
    {
        $access_key = config("unsplash.access_token");

        $response = Http::withHeader("Authorization", "Client-ID $access_key")
            ->get('https://api.unsplash.com/search/photos', ["query" => $query, "per_page" => 5]);
        return $response->object();
    }

}
