<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = "review";

    protected $fillable = [
        "user_id",
        "booking_id",
        "hotel_id",
        "tour_package_id",
        "rating",
        "comment",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function tourPackage()
    {
        return $this->belongsTo(TourPackage::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function __toString()
    {
        return "{$this->user} - {$this->rating}";
    }
}
