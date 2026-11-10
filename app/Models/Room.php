<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = "room";

    const STATUSES = [
        ["Available", "Available"],
        ["Occupied", "Occupied"],
        ["Cleaning", "Cleaning"],
        ["Maintenance", "Maintenance"],
    ];

    protected $fillable = ["room_type_id", "number", "status"];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // - true when no active booking overlaps the dates (check-out day is free again)
    public function isFree($check_in, $check_out, $ignore_id = null)
    {
        // - same "Y-m-d 00:00:00" form as the stored dates
        $check_in = Carbon::parse($check_in)->startOfDay();
        $check_out = Carbon::parse($check_out)->startOfDay();
        return !Booking::where("room_id", $this->id)
            ->whereNotIn("status", ["Cancelled"])
            ->where("check_in", "<", $check_out)
            ->where("check_out", ">", $check_in)
            ->when($ignore_id, fn ($q) => $q->where("id", "!=", $ignore_id))
            ->exists();
    }

    public function __toString()
    {
        return "Room {$this->number}";
    }
}
