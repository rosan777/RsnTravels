<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = "destination";

    protected $fillable = [
        "name",
        "country",
        "description",
        "best_time",
        "image_url",
        "show_on_site",
    ];

    protected $casts = ["show_on_site" => "boolean"];

    public function packages()
    {
        return $this->hasMany(TourPackage::class);
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }

    public function getPictureUrlAttribute()
    {
        return $this->image_url;
    }

    public function __toString()
    {
        return "{$this->name}, {$this->country}";
    }
}
