<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('ingredients')->nullable();
            $table->double("ingredientMatchScore")->nullable();
            $table->string("tag")->nullable();
            $table->text("instructions")->nullable();
            $table->string("difficulty")->default("neutral");
            $table->double("estimatedTimeMinutes")->nullable();
            $table->json("nutrition")->nullable();
            $table->text("images")->nullable();
            $table->json("extra")->nullable();
            $table->boolean("bookmarked")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};
