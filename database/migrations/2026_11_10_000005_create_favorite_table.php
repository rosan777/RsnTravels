<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("favorite", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            // - hotel, package or destination
            $table->string("item_type", 20);
            $table->unsignedBigInteger("item_id");
            $table->timestamps();
            $table->unique(["user_id", "item_type", "item_id"]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("favorite");
    }
};
