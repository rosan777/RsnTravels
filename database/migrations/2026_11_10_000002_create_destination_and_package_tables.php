<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("destination", function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("country", 100);
            $table->text("description")->nullable();
            $table->string("best_time", 100)->default("");
            $table->string("image_url", 500)->default("");
            $table->boolean("show_on_site")->default(false);
            $table->timestamps();
        });

        Schema::create("tour_package", function (Blueprint $table) {
            $table->id();
            $table->foreignId("destination_id")->constrained("destination")->cascadeOnDelete();
            $table->string("name", 150);
            $table->text("description")->nullable();
            $table->unsignedInteger("days")->default(1);
            $table->float("price")->default(0);
            // - one line per day, "Day 1: ..."
            $table->text("itinerary")->nullable();
            // - one line per item
            $table->text("includes")->nullable();
            $table->text("excludes")->nullable();
            // - nature, adventure, calm, family
            $table->string("interest", 20)->default("nature");
            $table->string("image_url", 500)->default("");
            $table->boolean("show_on_site")->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("tour_package");
        Schema::dropIfExists("destination");
    }
};
