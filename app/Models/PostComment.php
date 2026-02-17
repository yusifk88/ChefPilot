<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostComment extends Model
{
    use SoftDeletes;

    protected $table = 'post_comments';
    protected $fillable = ["user_id", "post_id", "comment", "created_at", "updated_at", "deleted_at"];

    public function commenter(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function post(): HasOne
    {
        return $this->hasOne(Post::class, 'id', 'post_id');
    }

}
