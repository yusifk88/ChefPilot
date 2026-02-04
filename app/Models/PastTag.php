<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PastTag extends Model
{
    protected $table = "past_tags";
    protected $fillable=["post_id","tag_id"];
}
