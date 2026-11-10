<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// - one piece of work given to an agent or a hotel staff member
class WorkTask extends Model
{
    protected $table = "work_task";

    // - kind => [label, icon]
    const KINDS = [
        "follow_up" => ["Call the customer", "fa-phone"],
        "arrival_prep" => ["Prepare arrival", "fa-door-open"],
        "housekeeping" => ["Clean the room", "fa-broom"],
        "general" => ["General task", "fa-clipboard-check"],
    ];

    protected $fillable = ["title", "detail", "kind", "booking_id", "assigned_to", "created_by", "status", "due_date", "done_at"];

    protected $casts = ["due_date" => "date", "done_at" => "datetime"];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, "assigned_to");
    }

    public function icon()
    {
        return self::KINDS[$this->kind][1] ?? "fa-clipboard-check";
    }
}
