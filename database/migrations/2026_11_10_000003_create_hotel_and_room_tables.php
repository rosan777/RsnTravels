<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("hotel", function (Blueprint $table) {
            $table->id();
            $table->foreignId("destination_id")->nullable()->constrained("destination")->nullOnDelete();
            $table->string("name", 150);
            $table->string("city", 100);
            $table->unsignedTinyInteger("stars")->default(3);
            $table->text("description")->nullable();
            // - comma separated: "Free Wi-Fi, Pool, Breakfast"
            $table->string("amenities", 500)->default("");
            $table->string("image_url", 500)->default("");
            $table->boolean("show_on_site")->default(false);
            $table->timestamps();
        });

        Schema::create("room_type", function (Blueprint $table) {
            $table->id();
            $table->foreignId("hotel_id")->constrained("hotel")->cascadeOnDelete();
            $table->string("name", 100);
            $table->text("description")->nullable();
            $table->unsignedTinyInteger("capacity")->default(2);
            // - price per night (BDT)
            $table->float("base_price")->default(0);
            $table->string("image_url", 500)->default("");
            $table->timestamps();
        });

        Schema::create("room", function (Blueprint $table) {
            $table->id();
            $table->foreignId("room_type_id")->constrained("room_type")->cascadeOnDelete();
            $table->string("number", 20);
            // - Available, Occupied, Cleaning
            $table->string("status", 20)->default("Available");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("room");
        Schema::dropIfExists("room_type");
        Schema::dropIfExists("hotel");
    }
};
