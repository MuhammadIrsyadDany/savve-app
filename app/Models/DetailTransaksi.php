<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $fillable = [
        'transaksi_id',
        'ukuran',
        'jenis_barang',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'jenis_barang' => 'array', // otomatis encode/decode JSON
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // Nama barang untuk ditampilkan
    public function getJenisBarangStringAttribute(): string
    {
        return implode(', ', $this->jenis_barang ?? []);
    }
}
