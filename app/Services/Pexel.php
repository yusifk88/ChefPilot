<?php

namespace app\Services;

use Illuminate\Support\Facades\Http;

class Pexel
{

    public static function search(string $query)
    {

        $response= Http::withToken(config('pexel.api_key'),"")
            ->get("https://api.pexels.com/videos/search",["query"=>$query,"per_page"=>5]);
        return $response->object();
    }
}
