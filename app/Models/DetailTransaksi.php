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
        'jenis_barang' => 'array',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriBarang::class, 'kategori_id');
    }

    // Helper: menampilkan jenis barang sebagai string
    public function getJenisBarangStringAttribute(): string
    {
        return implode(', ', $this->jenis_barang ?? []);
    }

    // Helper: menghitung jumlah jenis barang
    public function getJumlahJenisAttribute(): int
    {
        return count($this->jenis_barang ?? []);
    }
}
