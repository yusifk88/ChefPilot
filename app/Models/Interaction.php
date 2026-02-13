<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interaction extends Model
{
    protected $table = "interactions";
    protected $fillable=["post_id","user_id","type"];

    const LIKES ='likes';
}
