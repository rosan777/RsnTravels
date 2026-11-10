<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // - work given to a team member (agent or hotel staff)
        Schema::create("work_task", function (Blueprint $table) {
            $table->id();
            $table->string("title", 160);
            $table->text("detail")->nullable();
            // - follow_up (agent), arrival_prep (staff), housekeeping (staff), general
            $table->string("kind", 20)->default("general");
            $table->foreignId("booking_id")->nullable()->constrained("booking")->nullOnDelete();
            $table->foreignId("assigned_to")->nullable()->constrained("users")->nullOnDelete();
            $table->foreignId("created_by")->nullable()->constrained("users")->nullOnDelete();
            // - Open or Done
            $table->string("status", 10)->default("Open");
            $table->date("due_date")->nullable();
            $table->timestamp("done_at")->nullable();
            $table->timestamps();
        });

        // - a question from a customer to the travel agents
        Schema::create("inquiry", function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->cascadeOnDelete();
            $table->foreignId("agent_id")->nullable()->constrained("users")->nullOnDelete();
            $table->string("subject", 160);
            $table->text("message");
            $table->text("reply")->nullable();
            // - New or Answered
            $table->string("status", 10)->default("New");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists("inquiry");
        Schema::dropIfExists("work_task");
    }
};
