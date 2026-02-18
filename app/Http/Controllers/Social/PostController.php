<?php

namespace App\Http\Controllers\Social;

use App\Http\Controllers\Controller;
use App\Models\Follow;
use App\Models\Interaction;
use App\Models\Post;
use App\Models\PostComment;
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


            $post = Post::query()->with("user")->find($request->post_id);


            Interaction::create([
                "post_id" => $request->post_id,
                "user_id" => $request->user()->id,
                "type" => Interaction::LIKES
            ]);

            if ($post->user_id !== $request->user()->id) {

                $post->user->notify(new PostInteractionNotification(post: $post, type: Interaction::LIKES, user: $request->user()));

            }


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

    public function publicPost(string $ulid)
    {
        $post = Post::query()->with(["user", "recipe.photos"])->where("ulid", $ulid)->firstOrFail();

        return view("social.post", ["post" => $post]);
    }

    public function comment(Request $request)
    {
        $request->validate([
            "post_ulid" => "required|string|exists:posts,ulid",
            "comment" => "required|string",
        ]);

        $post = Post::where("ulid", $request->post_ulid)->firstOrFail();
        $newComment = new PostComment([
            "post_id" => $post->id,
            "comment" => $request->comment,
            "user_id" => $request->user()->id
        ]);

        $newComment->save();

        $newComment->load("commenter");
        $newComment->refresh();
        Interaction::create([
            "post_id" => $post->id,
            "user_id" => $request->user()->id,
            "type" => Interaction::COMMENTS
        ]);

       // if ($post->user_id !== $request->user()->id) {

            \Illuminate\Support\defer(function () use ($newComment) {

                $newComment->load(["post.user", "commenter"]);
                $newComment->refresh();
                $newComment->user->notify(new PostInteractionNotification(post: $newComment->post, type: Interaction::COMMENTS, user: $newComment->post->user));


            });

       // }

        return ResponseService::SuccessResponse(data: $newComment, message: "Comment posted successfully");

    }


    public function comments(string $ulid)
    {
        $post = Post::where("ulid", $ulid)->firstOrFail();
        $comments = PostComment::with("commenter")->where("post_id", $post->id)
            ->orderBy("id", "DESC")->cursorPaginate(30);

        return ResponseService::SuccessResponse(data: $comments, message: "Comments");
    }

    public function show(string $ulid)
    {

        $user = auth()->user();

        $post = Post::query()
            ->where("ulid", $ulid)
            ->select("posts.*")
            ->selectRaw(
                'EXISTS (
            SELECT 1 FROM follows
            WHERE follows.follower_id = ?
              AND follows.following_id = posts.user_id
        ) AS is_following_author',
                [$user->id]
            )
            ->withCount([
                'interactions as likes_count' => fn($q) => $q->where('type', Interaction::LIKES),
                'interactions as comments_count' => fn($q) => $q->where('type', Interaction::COMMENTS)
            ])
            ->selectRaw(
                'EXISTS (
            SELECT 1 FROM interactions
            WHERE interactions.post_id = posts.id
              AND interactions.user_id = ?
              AND interactions.type = ?
        ) AS has_liked',
                [$user->id, Interaction::LIKES]
            )
            ->selectRaw(
                'EXISTS (
            SELECT 1 FROM interactions
            WHERE interactions.post_id = posts.id
              AND interactions.user_id = ?
              AND interactions.type = ?
        ) AS has_commented',
                [$user->id, Interaction::COMMENTS]
            )
            ->with(["recipe.photos", "user"])
            ->withCount([
                'interactions as score' => function ($q) {
                    $q->where('created_at', '>=', now()->subDays(2));
                }
            ])->first();


        return ResponseService::SuccessResponse(data: $post, message: "Post retrieved successfully");
    }

    public function deleteComment(int $id)
    {
        $comment = PostComment::where("id", $id)->where("user_id", auth()->id())->firstOrFail();

        $comment->delete();
        return ResponseService::SuccessResponse(data: $comment, message: "Comment deleted successfully");
    }

}
