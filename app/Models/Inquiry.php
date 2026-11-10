<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// - a customer question, answered by a travel agent
class Inquiry extends Model
{
    protected $table = "inquiry";

    protected $fillable = ["user_id", "agent_id", "subject", "message", "reply", "status"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, "agent_id");
    }
}
