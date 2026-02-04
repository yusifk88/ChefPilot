<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = "posts";
    protected $fillable = ["recipe_id","caption","visibility"];

    public const string VISIBILITY_PUBLIC="public";
    public const string VISIBILITY_PRIVATE="private";

    public const string VISIBILITY_FOLLOWERS="followers";

}
