<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $table = 'recipes';
    protected $fillable = [
        "user_id", "name", "description", "ingredients",
        "ingredientMatchScore", "tag", "instructions", "difficulty",
        "estimatedTimeMinutes", "nutrition", "images", "extra","bookmarked"
    ];

    protected $casts = [
        "ingredients" => "array",
        "nutrition" => "array",
        "extra" => "array",
        "bookmarked" => "boolean"
    ];
}
