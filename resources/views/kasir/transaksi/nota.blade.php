<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ $transaksi->nomor_transaksi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            background: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .nota-wrapper {
            width: 80mm;
            background: white;
            padding: 6mm;
            box-shadow: 0 4px 24px rgba(0,0,0,0.1);
            border-radius: 4px;
        }

        /* Header */
        .header {
            text-align: center;
            padding-bottom: 8px;
            margin-bottom: 8px;
            border-bottom: 2px solid #000;
        }
        .header .logo {
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 4px;
            color: #000;
        }
        .header .tagline {
            font-size: 9px;
            color: #000;
            margin-top: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Nomor Transaksi */
        .nomor-box {
            border: 2px solid #000;
            text-align: center;
            padding: 8px 6px;
            margin: 8px 0;
        }
        .nomor-box .label {
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #000;
        }
        .nomor-box .nomor {
            font-size: 18px;
            font-weight: 900;
            letter-spacing: 1px;
            margin-top: 3px;
            color: #000;
        }

        /* Info rows */
        .info-section { margin: 8px 0; }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: 10px;
        }
        .info-row .label {
            color: #000;
            flex-shrink: 0;
            width: 38%;
        }
        .info-row .value {
            font-weight: 700;
            color: #000;
            text-align: right;
            flex: 1;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .divider-solid {
            border: none;
            border-top: 2px solid #000;
            margin: 8px 0;
        }

        /* Barang */
        .barang-title {
            font-size: 9px;
            font-weight: 700;
            color: #000;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }
        .barang-item {
            margin-bottom: 6px;
            padding-bottom: 6px;
            border-bottom: 1px dotted #000;
        }
        .barang-item:last-child { border-bottom: none; }
        .barang-name {
            font-weight: 700;
            color: #000;
            font-size: 11px;
        }
        .barang-detail {
            display: flex;
            justify-content: space-between;
            margin-top: 2px;
            font-size: 10px;
            color: #000;
        }
        .barang-subtotal { font-weight: 700; }

        /* Total */
        .total-box {
            border: 2px solid #000;
            padding: 8px;
            margin: 8px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000;
        }
        .total-value {
            font-size: 14px;
            font-weight: 900;
            color: #000;
        }

        /* Status */
        .status-badge {
            text-align: center;
            margin: 6px 0;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #000;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 10px;
            padding-top: 8px;
            border-top: 2px solid #000;
        }
        .footer .warning {
            font-size: 9px;
            font-weight: 700;
            color: #000;
            margin-bottom: 3px;
        }
        .footer .note {
            font-size: 8px;
            color: #000;
            line-height: 1.5;
        }
        .footer .thanks {
            font-size: 13px;
            font-weight: 900;
            color: #000;
            margin-top: 6px;
            letter-spacing: 3px;
        }
        .footer .copy {
            font-size: 8px;
            color: #000;
            margin-top: 3px;
        }

        /* Print actions */
        .print-actions {
            margin-top: 16px;
            display: flex;
            gap: 10px;
            width: 80mm;
        }
        .btn-print {
            flex: 1;
            padding: 10px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            font-family: sans-serif;
        }
        .btn-close {
            flex: 1;
            padding: 10px;
            background: #fff;
            color: #000;
            border: 2px solid #000;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            font-family: sans-serif;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .nota-wrapper {
                box-shadow: none;
                border-radius: 0;
                padding: 2mm;
                width: 80mm;
            }
            .print-actions { display: none; }
            @page {
                margin: 0;
                size: 80mm auto;
            }
        }
    </style>
</head>
<body>

<div class="nota-wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="logo">SAVVE</div>
        <div class="tagline">Layanan Penitipan Barang</div>
    </div>

    {{-- Nomor Transaksi --}}
    <div class="nomor-box">
        <div class="label">-- Transaction ID --</div>
        <div class="nomor">{{ $transaksi->nomor_transaksi }}</div>
    </div>

    {{-- Status --}}
    <div class="status-badge">[ BARANG DITITIPKAN ]</div>

    <hr class="divider">

    {{-- Info Transaksi --}}
    <div class="info-section">
        <div class="info-row">
            <span class="label">Event</span>
            <span class="value">{{ $transaksi->event->nama_event }}</span>
        </div>
        <div class="info-row">
            <span class="label">Penitip</span>
            <span class="value">{{ $transaksi->nama_penitip }}</span>
        </div>
        <div class="info-row">
            <span class="label">WhatsApp</span>
            <span class="value">{{ $transaksi->no_whatsapp }}</span>
        </div>
        <div class="info-row">
            <span class="label">Kasir</span>
            <span class="value">{{ $transaksi->kasir->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Waktu</span>
            <span class="value">{{ $transaksi->waktu_penitipan->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <hr class="divider">

    {{-- Daftar Barang --}}
    <div class="barang-title">--- Daftar Barang ---</div>
    @foreach($transaksi->details as $i => $detail)
    <div class="barang-item">
        <div class="barang-name">
            {{ $i + 1 }}. {{ $detail->nama_barang_custom ?? $detail->kategori->nama_kategori }}
        </div>
        <div class="barang-detail">
            <span>Ukuran {{ $detail->ukuran }} · Qty {{ $detail->jumlah }}</span>
            <span class="barang-subtotal">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <hr class="divider-solid">

    {{-- Total --}}
    <div class="total-box">
        <span class="total-label">Total Pembayaran</span>
        <span class="total-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
    </div>

    <hr class="divider">

    {{-- Footer --}}
    <div class="footer">
        <div class="warning">! Simpan nota ini baik-baik !</div>
        <div class="note">
            Tunjukkan nota ini saat pengambilan barang.<br>
            Barang tanpa nota tidak akan diserahkan.
        </div>
        <div class="thanks">TERIMA KASIH</div>
        <div class="copy">© {{ date('Y') }} Vendor Savve</div>
    </div>

</div>

{{-- Tombol Aksi --}}
<div class="print-actions">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Nota</button>
    <button class="btn-close" onclick="window.close()">✕ Tutup</button>
</div>

</body>
</html>