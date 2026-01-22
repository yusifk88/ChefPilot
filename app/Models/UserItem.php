<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserItem extends Model
{
    protected $table = 'user_items';
    protected $fillable = ["item_id", "user_id", "name", "category"];

    public function item(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class,"item_id", "id");
    }
}
