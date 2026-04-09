<?php

namespace App\Exports;

use App\Models\Transaksi;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TransaksiExport
{
    protected $event_id;
    protected $tanggal;
    protected $status;

    public function __construct($event_id = null, $tanggal = null, $status = null)
    {
        $this->event_id = $event_id;
        $this->tanggal  = $tanggal;
        $this->status   = $status;
    }

    public function download(string $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $headers = [
            'No. Transaksi',
            'Nama Penitip',
            'No. WhatsApp',
            'Event',
            'Kasir',
            'Barang',
            'Total (Rp)',
            'Status',
            'Waktu Penitipan',
            'Waktu Pengambilan'
        ];

        foreach ($headers as $i => $header) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}1", $header);
        }

        // Style header
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1D4ED8'],
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Data
        $transaksis = $this->getData();
        $row = 2;

        foreach ($transaksis as $t) {
            $barang = $t->details->map(function ($d) {
                $nama = $d->nama_barang_custom ?? $d->kategori->nama_kategori;
                return "{$nama} ({$d->ukuran}) x{$d->jumlah}";
            })->implode(', ');

            $sheet->setCellValue("A{$row}", $t->nomor_transaksi);
            $sheet->setCellValue("B{$row}", $t->nama_penitip);
            $sheet->setCellValue("C{$row}", $t->no_whatsapp);
            $sheet->setCellValue("D{$row}", $t->event->nama_event);
            $sheet->setCellValue("E{$row}", $t->kasir->name);
            $sheet->setCellValue("F{$row}", $barang);
            $sheet->setCellValue("G{$row}", $t->total_harga);
            $sheet->setCellValue("H{$row}", $t->status === 'dititip' ? 'Dititip' : 'Sudah Diambil');
            $sheet->setCellValue("I{$row}", $t->waktu_penitipan->format('d/m/Y H:i'));
            $sheet->setCellValue("J{$row}", $t->waktu_pengambilan?->format('d/m/Y H:i') ?? '-');
            $row++;
        }

        // Auto size kolom
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Download
        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function getData()
    {
        $query = Transaksi::with(['event', 'kasir', 'details.kategori']);

        if ($this->event_id) {
            $query->where('event_id', $this->event_id);
        }

        if ($this->tanggal) {
            $query->whereDate('created_at', $this->tanggal);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->latest()->get();
    }
}
