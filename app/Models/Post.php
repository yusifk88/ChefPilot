<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    public const string VISIBILITY_PUBLIC = "public";
    public const string VISIBILITY_PRIVATE = "private";
    public const string VISIBILITY_FOLLOWERS = "followers";
    protected $table = "posts";
    protected $fillable = ["recipe_id", "user_id", "caption", "visibility", "ulid"];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, "recipe_id", "id");

    }

    public function interactions()
    {
        return $this->hasMany(Interaction::class, "post_id", "id");
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, "post_tags", "post_id", "tag_id");
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }

}
