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
        'status',
        'waktu_penitipan',
        'waktu_pengambilan',
    ];

    protected $casts = [
        'waktu_penitipan'   => 'datetime',
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

    public function getTotalHargaAttribute()
    {
        return $this->details->sum('subtotal');
    }
}
