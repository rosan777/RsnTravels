<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = "hotel";

    protected $fillable = [
        "destination_id",
        "name",
        "city",
        "stars",
        "description",
        "amenities",
        "image_url",
        "show_on_site",
    ];

    protected $casts = ["show_on_site" => "boolean"];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function roomTypes()
    {
        return $this->hasMany(RoomType::class);
    }

    public function rooms()
    {
        return $this->hasManyThrough(Room::class, RoomType::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getPictureUrlAttribute()
    {
        return $this->image_url;
    }

    public function amenityList()
    {
        return array_values(array_filter(array_map("trim", explode(",", (string) $this->amenities))));
    }

    public function __toString()
    {
        return "{$this->name} - {$this->city}";
    }
}
