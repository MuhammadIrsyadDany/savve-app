<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisBarang;

class JenisBarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Ukuran S
            ['ukuran' => 'S', 'nama' => 'Dompet',       'urutan' => 1],
            ['ukuran' => 'S', 'nama' => 'HP / Gadget',  'urutan' => 2],
            ['ukuran' => 'S', 'nama' => 'Kacamata',     'urutan' => 3],
            ['ukuran' => 'S', 'nama' => 'Lainnya',      'urutan' => 99],

            // Ukuran M
            ['ukuran' => 'M', 'nama' => 'Parfum',       'urutan' => 1],
            ['ukuran' => 'M', 'nama' => 'Vape',         'urutan' => 2],
            ['ukuran' => 'M', 'nama' => 'Payung',       'urutan' => 3],
            ['ukuran' => 'M', 'nama' => 'Tas Kecil',    'urutan' => 4],
            ['ukuran' => 'M', 'nama' => 'Lainnya',      'urutan' => 99],

            // Ukuran L
            ['ukuran' => 'L', 'nama' => 'Jaket',        'urutan' => 1],
            ['ukuran' => 'L', 'nama' => 'Helm',         'urutan' => 2],
            ['ukuran' => 'L', 'nama' => 'Totebag',      'urutan' => 3],
            ['ukuran' => 'L', 'nama' => 'Ransel',       'urutan' => 4],
            ['ukuran' => 'L', 'nama' => 'Lainnya',      'urutan' => 99],

            // Ukuran XL
            ['ukuran' => 'XL', 'nama' => 'Koper',       'urutan' => 1],
            ['ukuran' => 'XL', 'nama' => 'Stroller',    'urutan' => 2],
            ['ukuran' => 'XL', 'nama' => 'Sepeda',      'urutan' => 3],
            ['ukuran' => 'XL', 'nama' => 'Lainnya',     'urutan' => 99],
        ];

        foreach ($data as $item) {
            JenisBarang::create($item);
        }
    }
}
