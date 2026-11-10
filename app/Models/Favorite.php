<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = "favorite";

    const TYPES = ["hotel", "package", "destination"];

    protected $fillable = ["user_id", "item_type", "item_id"];

    public function __toString()
    {
        return "{$this->item_type} #{$this->item_id}";
    }
}
