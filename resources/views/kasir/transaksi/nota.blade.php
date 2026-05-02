<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ $transaksi->nomor_transaksi }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            background: #f0f0f0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .nota-wrapper {
            width: 80mm;
            background: white;
            padding: 5mm 6mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            border-radius: 4px;
        }

        /* ── Logo ── */
        .logo-area {
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 2px solid #000;
            margin-bottom: 10px;
        }
        .logo-area img {
            height: 48px;
            width: auto;
            object-fit: contain;
            display: block;
            margin: 0 auto 3px;
        }
        .logo-area .tagline {
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #555;
        }

        /* ── Nama Penitip BESAR ── */
        .penitip-box {
            text-align: center;
            border: 2.5px solid #000;
            border-radius: 4px;
            padding: 8px 6px;
            margin-bottom: 8px;
        }
        .penitip-box .label {
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #555;
            margin-bottom: 3px;
        }
        .penitip-box .nama {
            font-size: 22px;
            font-weight: 900;
            color: #000;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1.1;
            word-break: break-word;
        }

        /* ── Nomor Transaksi BESAR ── */
        .nomor-box {
            text-align: center;
            background: #000;
            border-radius: 4px;
            padding: 8px 6px;
            margin-bottom: 8px;
        }
        .nomor-box .label {
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.6);
            margin-bottom: 3px;
        }
        .nomor-box .nomor {
            font-size: 17px;
            font-weight: 900;
            color: #fff;
            letter-spacing: 1px;
        }

        /* ── Status Badge ── */
        .status-badge {
            text-align: center;
            margin-bottom: 8px;
        }
        .status-badge span {
            display: inline-block;
            border: 1.5px solid #000;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 20px;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px dashed #aaa;
            margin: 7px 0;
        }
        .divider-solid {
            border: none;
            border-top: 2px solid #000;
            margin: 7px 0;
        }

        /* ── Info rows ── */
        .info-section { margin-bottom: 6px; }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 1.5px 0;
            font-size: 10px;
        }
        .info-row .lbl { color: #555; flex-shrink: 0; width: 42%; }
        .info-row .val { font-weight: 700; color: #000; text-align: right; }

        /* ── Daftar Barang ── */
        .barang-title {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: center;
            color: #000;
            margin-bottom: 6px;
        }
        .barang-item {
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px dotted #ccc;
        }
        .barang-item:last-child { border-bottom: none; }
        .barang-name {
            font-weight: 700;
            font-size: 12px;
            color: #000;
        }
        .barang-detail {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #444;
            margin-top: 1px;
        }
        .barang-subtotal { font-weight: 700; color: #000; }

        /* ── Total ── */
        .total-box {
            border: 2px solid #000;
            border-radius: 3px;
            padding: 7px 8px;
            margin: 7px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .total-value {
            font-size: 15px;
            font-weight: 900;
        }

        /* ── Footer ── */
        .footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 7px;
            border-top: 2px solid #000;
        }
        .footer .warning {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .footer .note {
            font-size: 8px;
            color: #555;
            line-height: 1.5;
        }
        .footer .thanks {
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 6px;
        }
        .footer .copy {
            font-size: 7px;
            color: #888;
            margin-top: 3px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── Action Buttons ── */
        .print-actions {
            margin-top: 16px;
            display: flex;
            gap: 10px;
            width: 80mm;
        }
        .btn-print {
            flex: 1;
            padding: 11px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            font-family: sans-serif;
            letter-spacing: 0.5px;
            transition: opacity 0.2s;
        }
        .btn-print:hover { opacity: 0.85; }
        .btn-close {
            flex: 1;
            padding: 11px;
            background: #fff;
            color: #000;
            border: 2px solid #000;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            font-family: sans-serif;
            transition: background 0.2s;
        }
        .btn-close:hover { background: #f5f5f5; }

        @media print {
            body {
                background: white;
                padding: 0;
                min-height: unset;
                justify-content: flex-start;
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

    {{-- Logo --}}
    <div class="logo-area">
        <img src="{{ asset('images/logo.png') }}" alt="Savve">
        <div class="tagline">Layanan Penitipan Barang</div>
    </div>

    {{-- Nama Penitip BESAR --}}
    <div class="penitip-box">
        <div class="label">Nama Penitip</div>
        <div class="nama">{{ $transaksi->nama_penitip }}</div>
    </div>

    {{-- Nomor Transaksi BESAR --}}
    <div class="nomor-box">
        <div class="label">— Kode Penitipan —</div>
        <div class="nomor">{{ $transaksi->nomor_transaksi }}</div>
    </div>

    {{-- Status --}}
    <div class="status-badge">
        <span>● Barang Dititipkan</span>
    </div>

    <hr class="divider">

    {{-- Info Transaksi --}}
    <div class="info-section">
        <div class="info-row">
            <span class="lbl">Event</span>
            <span class="val">{{ $transaksi->event->nama_event }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">No. WhatsApp</span>
            <span class="val">{{ $transaksi->no_whatsapp }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Kasir</span>
            <span class="val">{{ $transaksi->kasir->name }}</span>
        </div>
        <div class="info-row">
            <span class="lbl">Waktu</span>
            <span class="val">{{ $transaksi->waktu_penitipan->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <hr class="divider">

    {{-- Daftar Barang --}}
    <div class="barang-title">─── Daftar Barang ───</div>
    @foreach($transaksi->details as $i => $detail)
    <div class="barang-item">
        <div class="barang-name">
            {{ $i + 1 }}. {{ $detail->nama_barang_custom ?? $detail->kategori->nama_kategori }}
        </div>
        <div class="barang-detail">
            <span>Ukuran {{ $detail->ukuran }} &nbsp;·&nbsp; Qty {{ $detail->jumlah }}</span>
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
        <div class="warning">⚠ Simpan nota ini baik-baik</div>
        <div class="note">
            Tunjukkan nota ini saat pengambilan barang.<br>
            Barang tanpa nota tidak akan diserahkan.
        </div>
        <div class="thanks">Terima Kasih</div>
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