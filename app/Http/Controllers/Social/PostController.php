<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Services\ResponseService;
use app\Services\SocialFeed;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{

    /**g
     * get a mixture following, recommend and discover and shuffle it to form general
     * @return JsonResponse
     */

    public function generalFeed()
    {

        $feed = collect()
            ->merge(collect(SocialFeed::Following()->items())->take(12))
            ->merge(collect(SocialFeed::Recommended()->items())->take(5))
            ->merge(collect(SocialFeed::Discover()->items())->take(3))
            ->shuffle();

        return ResponseService::SuccessResponse(data: $feed, message: "General feed");

    }

    /**
     * get posts for following
     * @return JsonResponse
     */
    public function Following()
    {

        return ResponseService::SuccessResponse(data: SocialFeed::Following(), message: "Following feed");


    }

    /**
     * Get recommended posts to users
     * @return JsonResponse
     */
    public function Recommended()
    {

        return ResponseService::SuccessResponse(data: SocialFeed::Recommended(), message: "Recommended feed retrieved successfully");

    }

    /**
     * Get discover feed
     * @return JsonResponse
     */

    public function Discover()
    {
        return ResponseService::SuccessResponse(data: SocialFeed::Discover(), message: "Discover feed");

    }


}
