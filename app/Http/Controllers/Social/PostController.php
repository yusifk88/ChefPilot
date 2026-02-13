<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Interaction;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewFollowerNotification;
use App\Notifications\PostInteractionNotification;
use App\Services\ResponseService;
use app\Services\SocialFeed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function store(Request $request)
    {
        $request->validate([
            "recipe_id" => "required|numeric|exists:recipes,id",
            "visibility" => "required|in:public,private,followers,following",
            "caption" => "nullable|string",
            "tags" => "nullable|array",
        ]);


        $post = new Post([
            "recipe_id" => $request->input("recipe_id"),
            "visibility" => $request->string("visibility"),
            "caption" => $request->caption,
            "user_id" => $request->user()->id,
            "ulid" => Str::ulid()
        ]);
        $post->save();

        SocialFeed::AttacheTags($post, $request->tags);

        return ResponseService::SuccessResponse(data: $post, message: "Post created successfully");

    }

    public function follow(Request $request)
    {
        $request->validate([
            "user_id" => "required|numeric|exists:users,id",
        ]);

        if ($request->user()->id === $request->integer("user_id")) {

            return ResponseService::FailedResponse("You cannot follow yourself");

        }

        $followed = null;

        if (!Follow::where("follower_id", $request->user()->id)->where("following_id", $request->user_id)->exists()) {

            $followed = Follow::create([
                "follower_id" => $request->user()->id,
                "following_id" => $request->user_id
            ]);

            \Illuminate\Support\defer(function () use ($request) {

                $followedUser = User::find($request->user_id);

                if ($followedUser) {

                    $followedUser->notify(new NewFollowerNotification($request->user()));

                }

            });

        }


        return ResponseService::SuccessResponse(data: $followed, message: "Followed");
    }


    public function unfollow(Request $request)
    {
        $request->validate([
            "user_id" => "required|numeric|exists:users,id",
        ]);

        if ($request->user()->id === $request->integer("user_id")) {

            return ResponseService::FailedResponse("You cannot unfollow yourself");

        }

        if (Follow::where("follower_id", $request->user()->id)->where("following_id", $request->user_id)->exists()) {

            Follow::query()->where("follower_id", $request->user()->id)->where("following_id", $request->user_id)->delete();


        }


        return ResponseService::SuccessResponse(data: [], message: "Unfollowed successfully");

    }


    public function like(Request $request)
    {
        $request->validate([
            "post_id" => "required|numeric|exists:posts,id"
        ]);


        if (!Interaction::query()->where("post_id", $request->post_id)->where("user_id", $request->user()->id)->where("type", Interaction::LIKES)->exists()) {

            Interaction::create([
                "post_id" => $request->post_id,
                "user_id" => $request->user()->id,
                "type" => Interaction::LIKES
            ]);

            $post = Post::query()->with("user")->find($request->post_id);

            $post->user->notify(new PostInteractionNotification(post: $post, type: Interaction::LIKES, user: $request->user()));

        }

        return ResponseService::SuccessResponse(data: [], message: "Post Liked");

    }


    public function unlike(Request $request)
    {
        $request->validate([
            "post_id" => "required|numeric|exists:posts,id"
        ]);


        if (Interaction::query()->where("post_id", $request->post_id)->where("user_id", $request->user()->id)->where("type", Interaction::LIKES)->exists()) {


            Interaction::where("post_id", $request->post_id)->where("user_id", $request->user()->id)->where("type", Interaction::LIKES)->delete();

        }

        return ResponseService::SuccessResponse(data: [], message: "Post Unliked");

    }

}
