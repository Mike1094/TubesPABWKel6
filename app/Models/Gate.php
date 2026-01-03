<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_open' => 'boolean', // Deprecated, but keeping for safety during transition
    ];

    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}
