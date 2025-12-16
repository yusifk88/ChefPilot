<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserItem extends Model
{
    protected $table = 'user_items';
    protected $fillable = ["item_id", "user_id","name","category"];
}
