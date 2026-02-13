<?php

namespace app\Services;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SocialFeed
{


    /**
     * Help this user discover trending posts
     * @return CursorPaginator
     */

    public static function Discover(): CursorPaginator
    {
        $user = auth()->user();

        return self::postQuery($user)
            ->orderByDesc('score')
            ->orderByDesc('created_at')
            ->cursorPaginate(20);

    }

    private static function postQuery(User $user): Builder
    {
        return Post::query()
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
                'interactions as likes_count' => fn($q) => $q->where('type', 'like'),

                'interactions as comments_count' => fn($q) => $q->where('type', 'comment')
            ])
            ->selectRaw(
                'EXISTS (
            SELECT 1 FROM interactions
            WHERE interactions.post_id = posts.id
              AND interactions.user_id = ?
              AND interactions.type = ?
        ) AS has_liked',
                [$user->id, 'likes']
            )
            ->selectRaw(
                'EXISTS (
            SELECT 1 FROM interactions
            WHERE interactions.post_id = posts.id
              AND interactions.user_id = ?
              AND interactions.type = ?
        ) AS has_commented',
                [$user->id, 'comments']
            )
            ->with(["recipe.photos", "user"])
            ->withCount([
                'interactions as score' => function ($q) {
                    $q->where('created_at', '>=', now()->subDays(2));
                }
            ]);
    }

    /**
     * recommend post for this user
     * @return CursorPaginator
     */

    public static function Recommended(): CursorPaginator
    {

        $user = request()->user();

        $topTags = DB::table('interactions')
            ->join('post_tags', 'interactions.post_id', '=', 'post_tags.post_id')
            ->where('interactions.user_id', $user->id)
            ->select('tag_id', DB::raw('count(*) as score'))
            ->groupBy('tag_id')
            ->orderByDesc('score')
            ->limit(5)
            ->pluck('tag_id');

        $seenPostIds = DB::table('interactions')
            ->where('user_id', $user->id)
            ->pluck('post_id');

        return self::postQuery($user)
            ->whereHas('tags', fn($q) => $q->whereIn('tags.id', $topTags))
            ->whereNotIn('id', $seenPostIds)
            ->orderByDesc('created_at')
            ->cursorPaginate(20);
    }


    /**
     * get posts for users that this user is following
     * @return CursorPaginator
     */
    public static function Following(): CursorPaginator
    {
        $user = request()->user();

        return self::postQuery($user)
            ->whereNot("user_id", $user->id)
            ->orderByDesc('score')
            ->orderByDesc('created_at')
            ->cursorPaginate(20);


    }


    public static function AttacheTags(Post $post, $tags)
    {
        if ($tags) {

            foreach ($tags as $tag) {
                $existingTag = Tag::query()->where("name", $tag)->first();

                if ($existingTag) {

                    PostTag::query()->create([
                        'post_id' => $post->id,
                        'tag_id' => $existingTag->id
                    ]);

                } else {

                    $newTag = Tag::query()->create([
                        "name" => $tag,
                    ]);

                    PostTag::create([
                        'post_id' => $post->id,
                        'tag_id' => $newTag->id
                    ]);

                }
            }
        }

    }

}
