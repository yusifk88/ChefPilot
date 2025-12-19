<?php

namespace app\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Google
{

    public static function login(string $code): array
    {

        $client_id = config("services.google.client_id");

        $client_secret = config("services.google.client_secret");

        $url = "https://oauth2.googleapis.com/token?code=$code&client_id=$client_id&client_secret=$client_secret&grant_type=authorization_code&redirect_uri=";

        $test = Http::withHeader("Content-Type", "application/x-www-form-urlencoded")->post($url);


        return [
            "access_code"=>$test->object(),
            "status"=>$test->status(),
            "secret_code"=>$client_secret,
            "client_id"=>$client_id,
        ];
    }

    public static function refreshToken(string $refresh_token): ?object
    {
        $client_id = config("services.google.client_id");

        $client_secret = config("services.google.client_secret");

        $url = "https://oauth2.googleapis.com/token?refresh_token=$refresh_token&client_id=$client_id&client_secret=$client_secret&grant_type=authorization_code&redirect_uri=";

        $test = Http::withHeader("Content-Type", "application/x-www-form-urlencoded")->post($url);

        return $test->object();
    }
}
