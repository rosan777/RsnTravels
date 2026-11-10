<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $table = "season";

    const CHOICES = [
        ["Peak", "Peak"],
        ["Mid", "Mid"],
        ["Off", "Off"],
    ];

    protected $fillable = ["month", "season"];

    public function __toString()
    {
        return "{$this->month} - {$this->season}";
    }
}
