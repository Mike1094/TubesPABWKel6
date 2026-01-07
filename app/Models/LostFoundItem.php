<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LostFoundItem extends Model
{
    use HasFactory;

    // Sesuaikan dengan nama kolom di database migration (Bahasa Indonesia)
    protected $fillable = [
        'user_id',
        'jenis',           // update dari 'type'
        'nama_barang',     // update dari 'item_name'
        'deskripsi',       // update dari 'description'
        'lokasi_ditemukan',// update dari 'location'
        'foto',            // update dari 'image'
        'status',
        'linked_lost_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function linkedLostItem()
    {
        return $this->belongsTo(LostFoundItem::class, 'linked_lost_id');
    }
}
