<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TourPackage extends Model
{
    protected $table = "tour_package";

    // - what kind of trip it is (used by filters and the trip planner)
    const INTERESTS = [
        ["nature", "Nature"],
        ["adventure", "Adventure"],
        ["calm", "Calm"],
        ["family", "Family"],
    ];

    protected $fillable = [
        "destination_id",
        "name",
        "description",
        "days",
        "price",
        "itinerary",
        "includes",
        "excludes",
        "interest",
        "image_url",
        "show_on_site",
    ];

    protected $casts = ["show_on_site" => "boolean"];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getPictureUrlAttribute()
    {
        return $this->image_url;
    }

    // - text columns are one item per line
    public function lines($field)
    {
        return array_values(array_filter(array_map("trim", explode("\n", (string) $this->$field))));
    }

    public function __toString()
    {
        return "{$this->name} - {$this->days} days - {$this->price}";
    }
}
