<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $table = "room_type";

    protected $fillable = [
        "hotel_id",
        "name",
        "description",
        "capacity",
        "base_price",
        "image_url",
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function getPictureUrlAttribute()
    {
        return $this->image_url ?: $this->hotel->image_url;
    }

    public function __toString()
    {
        return "{$this->hotel->name} - {$this->name} - {$this->base_price}";
    }
}
