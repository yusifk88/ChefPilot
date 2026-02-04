<?php

namespace app\Services;

use App\Models\Post;
use App\Models\PostTag;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;

class SocialFeed
{

    /**
     * Help this user discover trending posts
     * @return CursorPaginator
     */

    public static function Discover(): CursorPaginator
    {
        return Post::query()
            ->withCount([
                'interactions as score' => function ($q) {
                    $q->where('created_at', '>=', now()->subDays(2));
                }
            ])
            ->orderByDesc('score')
            ->orderByDesc('created_at')
            ->cursorPaginate(20);

    }


    /**
     * recommend post for this user
     * @return CursorPaginator
     */

    public static function Recommended(): CursorPaginator
    {

        $user = request()->user();

        $topTags = DB::table('interactions')
            ->join('post_tag', 'interactions.post_id', '=', 'post_tag.post_id')
            ->where('interactions.user_id', $user->id)
            ->select('tag_id', DB::raw('count(*) as score'))
            ->groupBy('tag_id')
            ->orderByDesc('score')
            ->limit(5)
            ->pluck('tag_id');

        $seenPostIds = DB::table('interactions')
            ->where('user_id', $user->id)
            ->pluck('post_id');

        return Post::query()
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

        return Post::query()
            ->whereIn('user_id', function ($q) use ($user) {
                $q->select('following_id')
                    ->from('follows')
                    ->where('follower_id', $user->id);
            })
            ->withCount([
                'interactions as interaction_score' => function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                }
            ])
            ->orderByDesc('interaction_score')
            ->orderByDesc('created_at')
            ->cursorPaginate(20);


    }


    public static function AttacheTags(Post $post,$tags)
    {
        if ($tags){

            foreach ($tags as $tag){
                $existingTag = Tag::query()->where("name", $tag)->first();

                if ($existingTag){

                    PostTag::query()->create([
                        'post_id' => $post->id,
                        'tag_id' => $existingTag->id
                    ]);

                } else{

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
