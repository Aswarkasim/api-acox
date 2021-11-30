<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    function driver()
    {
        return $this->BelongsTo(User::class, 'driver_id', 'id');
    }

    function user()
    {
        return $this->belongsTo(User::class);
    }
}
