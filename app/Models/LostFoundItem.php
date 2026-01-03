<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostFoundItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'type', 'item_name', 'description', 'location', 'image', 'status', 'linked_lost_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function linkedLostItem()
    {
        return $this->belongsTo(LostFoundItem::class, 'linked_lost_id');
    }
}
