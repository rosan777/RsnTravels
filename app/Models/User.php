<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

// - plain login user (like Django's User); role and phone live in UserEx
class User extends Authenticatable
{
    protected $fillable = ["name", "email", "password"];

    protected $hidden = ["password", "remember_token"];

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
        ];
    }

    public function userEx()
    {
        return $this->hasOne(UserEx::class);
    }

    public function __toString()
    {
        return "{$this->name}";
    }
}
