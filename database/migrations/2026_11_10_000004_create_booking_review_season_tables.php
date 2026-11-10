<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("booking", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            // - Hotel or Package
            $table->string("booking_type", 20)->default("Hotel");
            $table->foreignId("room_id")->nullable()->constrained("room")->cascadeOnDelete();
            $table->foreignId("tour_package_id")->nullable()->constrained("tour_package")->cascadeOnDelete();
            $table->date("check_in");
            $table->date("check_out");
            $table->unsignedInteger("guests")->default(1);
            $table->float("total")->default(0);
            // - Pending, Confirmed, CheckedIn, Completed, Cancelled
            $table->string("status", 20)->default("Pending");
            $table->string("payment_method", 20)->default("PayAtHotel");
            $table->string("payment_status", 20)->default("UNPAID");
            $table->timestamps();
            $table->index(["room_id", "check_in", "check_out"]);
        });

        Schema::create("review", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("booking_id")->unique()->constrained("booking")->cascadeOnDelete();
            $table->foreignId("hotel_id")->nullable()->constrained("hotel")->cascadeOnDelete();
            $table->foreignId("tour_package_id")->nullable()->constrained("tour_package")->cascadeOnDelete();
            $table->unsignedTinyInteger("rating")->default(5);
            $table->text("comment")->nullable();
            $table->timestamps();
        });

        Schema::create("season", function (Blueprint $table) {
            $table->id();
            // - 1 to 12
            $table->unsignedTinyInteger("month")->unique();
            // - Peak, Mid, Off
            $table->string("season", 10)->default("Mid");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("season");
        Schema::dropIfExists("review");
        Schema::dropIfExists("booking");
    }
};
