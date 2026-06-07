<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'nomor_transaksi',
        'event_id',
        'kasir_id',
        'nama_penitip',
        'no_whatsapp',
        'metode_bayar',
        'status',
        'foto_penitipan',
        'foto_pengambilan',
        'waktu_penitipan',
        'waktu_pengambilan',
    ];

    protected $casts = [
        'waktu_penitipan'  => 'datetime',
        'waktu_pengambilan' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    // Total harga dari semua detail
    public function getTotalHargaAttribute(): float
    {
        return $this->details->sum('subtotal');
    }
}
