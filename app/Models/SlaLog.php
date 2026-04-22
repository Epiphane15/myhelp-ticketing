<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlaLog extends Model
{
    protected $guarded = [];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
