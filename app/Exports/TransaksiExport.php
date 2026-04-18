<?php

namespace App\Exports;

use App\Models\Transaksi;
use App\Models\Event;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Font;

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
        $sheet->setTitle('Laporan Transaksi');

        // ═══ KONFIGURASI LEBAR KOLOM ═══
        $sheet->getColumnDimension('A')->setWidth(6);
        $sheet->getColumnDimension('B')->setWidth(22);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(16);
        $sheet->getColumnDimension('E')->setWidth(28);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(16);
        $sheet->getColumnDimension('I')->setWidth(16);
        $sheet->getColumnDimension('J')->setWidth(18);
        $sheet->getColumnDimension('K')->setWidth(18);

        // ═══ HEADER LAPORAN ═══
        $event = $this->event_id ? Event::find($this->event_id) : null;

        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'LAPORAN TRANSAKSI PENITIPAN BARANG');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F2044']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(32);

        $sheet->mergeCells('A2:K2');
        $sheet->setCellValue('A2', 'Vendor Savve — Storage Management System');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 10, 'color' => ['rgb' => 'FFFFFF'], 'italic' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A3A6B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // ═══ INFO LAPORAN ═══
        $sheet->mergeCells('A3:K3');
        $sheet->getStyle('A3')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFF']],
        ]);
        $sheet->getRowDimension(3)->setRowHeight(8);

        $infoRow = 4;
        $infos = [
            ['Event',       $event ? $event->nama_event : 'Semua Event'],
            ['Periode',     $this->tanggal ? \Carbon\Carbon::parse($this->tanggal)->format('d F Y') : ($event ? $event->tanggal_mulai->format('d M Y') . ' — ' . $event->tanggal_selesai->format('d M Y') : 'Semua Tanggal')],
            ['Status',      $this->status ? ($this->status === 'dititip' ? 'Dititipkan' : 'Sudah Diambil') : 'Semua Status'],
            ['Dicetak',     now()->format('d F Y, H:i') . ' WIB'],
        ];

        foreach ($infos as $i => $info) {
            $col1 = chr(65 + ($i * 2 < 8 ? $i * 2 : $i * 2));
            $col2 = chr(66 + ($i * 2 < 8 ? $i * 2 : $i * 2));
            $sheet->setCellValue($col1 . $infoRow, $info[0] . ':');
            $sheet->setCellValue($col2 . $infoRow, $info[1]);
            $sheet->getStyle($col1 . $infoRow)->applyFromArray([
                'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '64748B']],
            ]);
            $sheet->getStyle($col2 . $infoRow)->applyFromArray([
                'font' => ['size' => 9, 'color' => ['rgb' => '1E293B']],
            ]);
        }
        $sheet->getRowDimension($infoRow)->setRowHeight(18);

        $sheet->mergeCells('A5:K5');
        $sheet->getStyle('A5')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFF']],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(8);

        // ═══ HEADER TABEL ═══
        $headerRow = 6;
        $headers = ['No', 'No. Transaksi', 'Nama Penitip', 'No. WhatsApp', 'Event', 'Kasir', 'Detail Barang', 'Total (Rp)', 'Status', 'Waktu Penitipan', 'Waktu Pengambilan'];

        foreach ($headers as $i => $header) {
            $col = chr(65 + $i);
            $sheet->setCellValue($col . $headerRow, $header);
        }

        $sheet->getStyle('A' . $headerRow . ':K' . $headerRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A3A6B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '2D5AA0']]],
        ]);
        $sheet->getRowDimension($headerRow)->setRowHeight(22);

        // ═══ DATA ═══
        $transaksis = $this->getData();
        $row = $headerRow + 1;
        $no = 1;

        foreach ($transaksis as $t) {
            $isEven = ($no % 2 === 0);
            $bgColor = $isEven ? 'F8FAFF' : 'FFFFFF';

            $barang = $t->details->map(function ($d) {
                $nama = $d->nama_barang_custom ?? $d->kategori->nama_kategori;
                return "{$nama} (Ukuran {$d->ukuran}) x{$d->jumlah} = Rp " . number_format($d->subtotal, 0, ',', '.');
            })->implode("\n");

            $statusText = $t->status === 'dititip' ? 'Dititipkan' : 'Sudah Diambil';
            $statusColor = $t->status === 'dititip' ? '1D4ED8' : '15803D';
            $statusBg    = $t->status === 'dititip' ? 'EFF6FF' : 'F0FDF4';

            $values = [
                'A' => $no,
                'B' => $t->nomor_transaksi,
                'C' => $t->nama_penitip,
                'D' => $t->no_whatsapp,
                'E' => $t->event->nama_event,
                'F' => $t->kasir->name,
                'G' => $barang,
                'H' => $t->total_harga,
                'I' => $statusText,
                'J' => $t->waktu_penitipan->format('d/m/Y H:i'),
                'K' => $t->waktu_pengambilan?->format('d/m/Y H:i') ?? '-',
            ];

            foreach ($values as $col => $val) {
                $sheet->setCellValue($col . $row, $val);
            }

            // Style per baris
            $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bgColor]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
                'alignment' => ['vertical' => Alignment::VERTICAL_TOP, 'wrapText' => true],
            ]);

            // Style khusus per kolom
            $sheet->getStyle('A' . $row)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['bold' => true, 'color' => ['rgb' => '94A3B8']],
            ]);
            $sheet->getStyle('B' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '1A3A6B'], 'name' => 'Courier New'],
            ]);
            $sheet->getStyle('H' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '1A3A6B']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
                'numberFormat' => ['formatCode' => '#,##0'],
            ]);
            $sheet->getStyle('I' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => $statusColor]],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $statusBg]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getStyle('J' . $row)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getStyle('K' . $row)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font' => ['color' => ['rgb' => $t->waktu_pengambilan ? '15803D' : '94A3B8']],
            ]);

            $sheet->getRowDimension($row)->setRowHeight(-1);
            $row++;
            $no++;
        }

        // ═══ FOOTER SUMMARY ═══
        $sheet->mergeCells('A' . $row . ':K' . $row);
        $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8FAFF']],
        ]);
        $sheet->getRowDimension($row)->setRowHeight(8);
        $row++;

        $summaryData = [
            ['Total Transaksi', $no - 1],
            ['Total Dititipkan', $transaksis->where('status', 'dititip')->count()],
            ['Total Diambil', $transaksis->where('status', 'sudah_diambil')->count()],
            ['Total Pendapatan', 'Rp ' . number_format($transaksis->sum(fn($t) => $t->total_harga), 0, ',', '.')],
        ];

        foreach ($summaryData as $i => $s) {
            $col1 = chr(65 + ($i * 2 < 8 ? $i * 2 : $i * 2));
            $col2 = chr(66 + ($i * 2 < 8 ? $i * 2 : $i * 2));
            $sheet->setCellValue($col1 . $row, $s[0]);
            $sheet->setCellValue($col2 . $row, $s[1]);
            $sheet->getStyle($col1 . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 9, 'color' => ['rgb' => '64748B']],
            ]);
            $sheet->getStyle($col2 . $row)->applyFromArray([
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => '0F2044']],
            ]);
        }
        $sheet->getRowDimension($row)->setRowHeight(20);

        $row++;
        $sheet->mergeCells('A' . $row . ':K' . $row);
        $sheet->setCellValue('A' . $row, '© ' . date('Y') . ' Vendor Savve — Storage Management System');
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['italic' => true, 'size' => 8, 'color' => ['rgb' => '94A3B8']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ═══ FREEZE PANES & PRINT SETTINGS ═══
        $sheet->freezePane('A' . ($headerRow + 1));
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getHeaderFooter()->setOddHeader('&C&B Laporan Transaksi — Vendor Savve');
        $sheet->getHeaderFooter()->setOddFooter('&L&D &T&R Halaman &P dari &N');

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
