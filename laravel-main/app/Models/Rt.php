<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rt extends Model
{
    use HasFactory;

    protected $table = 'rt';
    
    protected $fillable = [
        'rw_id', 
        'kelurahan_id', 
        'nomor_rt', 
        'koordinat_lat', 
        'koordinat_lng',
        'nomor_rt',
    'rw_id'
    ];

    // Relationships
    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
    }

    public function kelurahan(): BelongsTo
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function wargas(): HasMany
    {
        return $this->hasMany(Warga::class);
    }

    public function kaders(): HasMany
    {
        return $this->hasMany(Kader::class);
    }

    public function laporans(): HasMany
    {
        return $this->hasMany(Laporan::class);
    }
}