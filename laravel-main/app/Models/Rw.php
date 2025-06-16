<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rw extends Model
{
    use HasFactory;

    protected $table = 'rw'; // Sesuaikan dengan nama tabel di database
    
    protected $fillable = ['nomor_rw', 'kelurahan_id'];
    // Relationships
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

     public function rts()
    {
        return $this->hasMany(Rt::class);
    }

    public function wargas()
    {
        return $this->hasMany(Warga::class);
    }

    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }
}