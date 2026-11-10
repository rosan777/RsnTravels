<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create("user_ex", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->unique()->constrained("users")->cascadeOnDelete();
            // - phone
            $table->string("phone", 20)->nullable();
            // - user type: owner, staff, customer
            $table->string("user_type", 20)->default("customer");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("user_ex");
    }
};
