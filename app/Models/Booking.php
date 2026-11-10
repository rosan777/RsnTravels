<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = "booking";

    const TYPES = [
        ["Hotel", "Hotel"],
        ["Package", "Package"],
    ];

    const CHOICES = [
        ["Pending", "Pending"],
        ["Confirmed", "Confirmed"],
        ["CheckedIn", "Checked-in"],
        ["Completed", "Completed"],
        ["Cancelled", "Cancelled"],
    ];

    const PAYMENT_METHODS = [
        ["bKash", "bKash"],
        ["Nagad", "Nagad"],
        ["Card", "Card"],
        ["PayAtHotel", "Pay at hotel"],
    ];

    const PAYMENT_CHOICES = [
        ["PAID", "PAID"],
        ["UNPAID", "UNPAID"],
    ];

    protected $fillable = [
        "user_id",
        "booking_type",
        "room_id",
        "tour_package_id",
        "check_in",
        "check_out",
        "guests",
        "total",
        "status",
        "payment_method",
        "payment_status",
    ];

    protected $casts = [
        "check_in" => "date",
        "check_out" => "date",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function tourPackage()
    {
        return $this->belongsTo(TourPackage::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function nights()
    {
        return max(1, (int) Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out)));
    }

    // - short name for lists: hotel + room, or the package name
    public function title()
    {
        if ($this->booking_type == "Package") {
            return $this->tourPackage ? $this->tourPackage->name : "Package";
        }
        return $this->room ? "{$this->room->roomType->hotel->name} - {$this->room->roomType->name}" : "Hotel";
    }

    public function __toString()
    {
        return "{$this->user} - {$this->title()} - {$this->total} - {$this->status}";
    }
}
