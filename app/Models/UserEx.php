<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEx extends Model
{
    protected $table = "user_ex";

    // - user types
    const USER_TYPES = [
        ["owner", "Agency Owner"],
        ["staff", "Hotel Staff"],
        ["customer", "Customer"],
    ];

    protected $fillable = ["user_id", "phone", "user_type"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function __toString()
    {
        return "{$this->user->email} - {$this->user_type}";
    }
}
