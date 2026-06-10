@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $totalLembar = 1 + $transaksi->details->count();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ $transaksi->nomor_transaksi }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            line-height: 1.45;
            background: #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 16px 48px;
            min-height: 100vh;
        }

        .nota-wrapper {
            width: 80mm;
            background: #fff;
            padding: 4.5mm 5.5mm;
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.18);
        }

        .nota-separator {
            width: 80mm;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 0;
            color: #aaa;
            font-size: 7.5px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            text-transform: uppercase;
            user-select: none;
        }

        .nota-separator::before,
        .nota-separator::after {
            content: '';
            flex: 1;
            border-top: 1px dashed #bbb;
        }

        .doc-title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1.5px solid #000;
            padding: 3px 6px;
            margin-bottom: 6px;
        }

        .doc-title-bar .doc-title {
            font-size: 8.5px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #000;
        }

        .doc-title-bar .doc-lembar {
            font-size: 7px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #555;
            border-left: 1px solid #ccc;
            padding-left: 6px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 5px;
            margin-bottom: 5px;
            border-bottom: 2px solid #000;
            gap: 6px;
        }

        .header img {
            height: 32px;
            width: auto;
            object-fit: contain;
            filter: grayscale(100%) contrast(160%);
        }

        .header-right {
            text-align: right;
        }

        .header-right .brand {
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #000;
            line-height: 1;
        }

        .header-right .tagline {
            font-size: 6.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #666;
            margin-top: 2px;
        }

        .identity-block {
            display: flex;
            align-items: stretch;
            border: 1.5px solid #000;
            margin-bottom: 5px;
        }

        .identity-left {
            flex: 1;
            min-width: 0;
            padding: 5px 6px;
            border-right: 1.5px solid #000;
        }

        .identity-left .lbl {
            font-size: 6.5px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 1px;
        }

        .identity-left .nama {
            font-size: 15px;
            font-weight: 900;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.1;
            word-break: break-word;
        }

        .identity-left .kode-wrap {
            margin-top: 5px;
            padding-top: 4px;
            border-top: 1px dashed #bbb;
        }

        .identity-left .kode {
            font-size: 9px;
            font-weight: 700;
            color: #000;
            letter-spacing: 1px;
        }

        .identity-right {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5px 5px 3px;
            gap: 2px;
        }

        .identity-right svg {
            display: block;
            width: 50px !important;
            height: 50px !important;
        }

        .identity-right .qr-label {
            font-size: 5.5px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #666;
            text-align: center;
            line-height: 1.3;
        }

        /* Label kategori — badge besar di label barang */
        .ukuran-badge {
            display: inline-block;
            border: 2px solid #000;
            font-size: 28px;
            font-weight: 900;
            width: 44px;
            height: 44px;
            line-height: 40px;
            text-align: center;
            letter-spacing: 0;
            margin-bottom: 3px;
        }

        .divider {
            border: none;
            border-top: 1px dashed #aaa;
            margin: 4px 0;
        }

        .divider-solid {
            border: none;
            border-top: 1.5px solid #000;
            margin: 4px 0;
        }

        .info-section {
            margin-bottom: 4px;
        }

        .info-row {
            display: flex;
            align-items: baseline;
            padding: 1px 0;
            font-size: 9px;
            gap: 2px;
        }

        .info-row .lbl {
            color: #555;
            width: 36%;
            flex-shrink: 0;
        }

        .info-row .sep {
            color: #999;
            flex-shrink: 0;
        }

        .info-row .val {
            font-weight: 700;
            color: #000;
            flex: 1;
            text-align: right;
        }

        .section-title {
            font-size: 7px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: center;
            color: #000;
            margin: 4px 0;
        }

        .barang-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-bottom: 2px;
        }

        .barang-table thead tr th {
            font-size: 7px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #555;
            padding: 2px 2px 3px;
            border-bottom: 1px solid #000;
            text-align: left;
        }

        .barang-table thead tr th:last-child {
            text-align: right;
        }

        .barang-table tbody tr td {
            padding: 3px 2px;
            vertical-align: top;
            border-bottom: 1px dotted #ccc;
            color: #000;
        }

        .barang-table tbody tr:last-child td {
            border-bottom: none;
        }

        .barang-table .col-no {
            width: 10%;
            color: #555;
        }

        .barang-table .col-nama {
            width: 50%;
            font-weight: 700;
        }

        .barang-table .col-detail {
            width: 20%;
            color: #555;
            font-size: 8px;
        }

        .barang-table .col-harga {
            width: 20%;
            font-weight: 700;
            text-align: right;
        }

        .total-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1.5px solid #000;
            padding: 4px 6px;
            margin: 5px 0;
        }

        .total-box .total-label {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .total-box .total-value {
            font-size: 13px;
            font-weight: 900;
            letter-spacing: 0.5px;
        }

        .ttd-row {
            display: flex;
            gap: 8px;
            margin-top: 5px;
        }

        .ttd-box {
            flex: 1;
            border: 1px solid #000;
            padding: 4px 5px;
            min-height: 28px;
        }

        .ttd-box .ttd-label {
            font-size: 6.5px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #666;
        }

        .ttd-box .ttd-line {
            border-bottom: 1px solid #ccc;
            margin-top: 16px;
        }

        .footer {
            text-align: center;
            margin-top: 6px;
            padding-top: 5px;
            border-top: 1.5px solid #000;
        }

        .footer .f-warning {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .footer .f-note {
            font-size: 7.5px;
            color: #444;
            line-height: 1.55;
        }

        .footer .f-thanks {
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .footer .f-copy {
            font-size: 6.5px;
            color: #999;
            margin-top: 2px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .footer-barang {
            text-align: center;
            margin-top: 6px;
            padding-top: 5px;
            border-top: 1px dashed #000;
        }

        .footer-barang .f-warning {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .footer-barang .f-note {
            font-size: 7.5px;
            color: #444;
            line-height: 1.55;
        }

        .print-actions {
            margin-top: 20px;
            display: flex;
            gap: 8px;
            width: 80mm;
        }

        .btn-print {
            flex: 1;
            padding: 10px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            font-family: sans-serif;
            letter-spacing: 0.5px;
            transition: opacity 0.2s;
        }

        .btn-print:hover {
            opacity: 0.8;
        }

        .btn-close {
            flex: 1;
            padding: 10px;
            background: #fff;
            color: #000;
            border: 2px solid #000;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            font-family: sans-serif;
            transition: background 0.15s;
        }

        .btn-close:hover {
            background: #f0f0f0;
        }

        @media print {
            body {
                background: white !important;
                padding: 0;
                align-items: flex-start;
            }

            .nota-wrapper {
                box-shadow: none;
                width: 80mm;
                padding: 3mm 4.5mm;
                page-break-after: always;
                break-after: page;
            }

            .nota-wrapper:last-of-type {
                page-break-after: avoid;
                break-after: avoid;
            }

            .nota-separator,
            .print-actions {
                display: none !important;
            }

            * {
                background: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                margin: 0;
                size: 80mm auto;
            }
        }
    </style>
</head>

<body>

    {{-- ══════════════════════════════════════════
         LEMBAR 1 — NOTA CUSTOMER (semua barang)
    ══════════════════════════════════════════ --}}
    <div class="nota-wrapper">

        <div class="doc-title-bar">
            <span class="doc-title">Bukti Transaksi Penitipan</span>
            <span class="doc-lembar">Lembar 1 / {{ $totalLembar }}</span>
        </div>

        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Savve">
            <div class="header-right">
                <div class="brand">Savve</div>
                <div class="tagline">Layanan Penitipan Barang</div>
            </div>
        </div>

        <div class="identity-block">
            <div class="identity-left">
                <div class="lbl">Nama Penitip</div>
                <div class="nama">{{ $transaksi->nama_penitip }}</div>
                <div class="kode-wrap">
                    <div class="lbl">Kode Penitipan</div>
                    <div class="kode">{{ $transaksi->nomor_transaksi }}</div>
                </div>
            </div>
            <div class="identity-right">
                {!! QrCode::size(50)->margin(1)->errorCorrection('H')->style('square')->generate($transaksi->nomor_transaksi) !!}
                <div class="qr-label">Scan saat<br>pengambilan</div>
            </div>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="lbl">Event</span><span class="sep">:</span>
                <span class="val">{{ $transaksi->event->nama_event }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Waktu Penitipan</span><span class="sep">:</span>
                <span class="val">{{ $transaksi->waktu_penitipan->format('d/m/Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">No. WhatsApp</span><span class="sep">:</span>
                <span class="val">{{ $transaksi->no_whatsapp }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Metode Bayar</span><span class="sep">:</span>
                <span class="val">{{ $transaksi->metode_bayar }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Kasir</span><span class="sep">:</span>
                <span class="val">{{ $transaksi->kasir->name }}</span>
            </div>
        </div>

        <hr class="divider">

        <div class="section-title">— Daftar Barang —</div>
        <table class="barang-table">
            <thead>
                <tr>
                    <th class="col-no">#</th>
                    <th class="col-nama">Nama Barang</th>
                    <th class="col-detail">Ukuran</th>
                    <th class="col-harga">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->details as $i => $detail)
                    <tr>
                        <td class="col-no">{{ $i + 1 }}</td>
                        <td class="col-nama">{{ implode(', ', $detail->jenis_barang ?? []) }}</td>
                        <td class="col-detail">{{ $detail->ukuran }}</td>
                        <td class="col-harga">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr class="divider-solid">

        <div class="total-box">
            <span class="total-label">Total Pembayaran</span>
            <span class="total-value">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
        </div>

        <div class="ttd-row">
            <div class="ttd-box">
                <div class="ttd-label">Tanda Terima Penitip</div>
                <div class="ttd-line"></div>
            </div>
            <div class="ttd-box">
                <div class="ttd-label">Paraf Kasir</div>
                <div class="ttd-line"></div>
            </div>
        </div>

        <div class="footer">
            <div class="f-warning">Simpan nota ini sebagai bukti transaksi</div>
            <div class="f-note">
                Tunjukkan nota atau scan QR saat pengambilan barang.<br>
                Barang tidak akan diserahkan tanpa bukti transaksi ini.
            </div>
            <div class="f-thanks">Terima Kasih</div>
            <div class="f-copy">© {{ date('Y') }} Vendor Savve</div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         LEMBAR 2, 3, dst — LABEL PER KATEGORI
    ══════════════════════════════════════════ --}}
    @foreach ($transaksi->details as $i => $detail)
        <div class="nota-separator">
            &#8212; potong di sini &nbsp;&middot;&nbsp; lembar {{ $i + 2 }} ditempel pada barang &#8212;
        </div>

        <div class="nota-wrapper">

            <div class="doc-title-bar">
                <span class="doc-title">Label Identifikasi Barang</span>
                <span class="doc-lembar">Lembar {{ $i + 2 }} / {{ $totalLembar }}</span>
            </div>

            <div class="header">
                <img src="{{ asset('images/logo.png') }}" alt="Savve">
                <div class="header-right">
                    <div class="brand">Savve</div>
                    <div class="tagline">Layanan Penitipan Barang</div>
                </div>
            </div>

            {{-- Identitas + QR --}}
            <div class="identity-block">
                <div class="identity-left">
                    <div class="lbl">Nama Penitip</div>
                    <div class="nama">{{ $transaksi->nama_penitip }}</div>
                    <div class="kode-wrap">
                        <div class="lbl">Kode Penitipan</div>
                        <div class="kode">{{ $transaksi->nomor_transaksi }}</div>
                    </div>
                </div>
                <div class="identity-right">
                    {!! QrCode::size(50)->margin(1)->errorCorrection('H')->style('square')->generate($transaksi->nomor_transaksi) !!}
                    <div class="qr-label">Scan saat<br>pengambilan</div>
                </div>
            </div>

            {{-- Info ringkas --}}
            <div class="info-section">
                <div class="info-row">
                    <span class="lbl">Event</span><span class="sep">:</span>
                    <span class="val">{{ $transaksi->event->nama_event }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">Waktu Penitipan</span><span class="sep">:</span>
                    <span class="val">{{ $transaksi->waktu_penitipan->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="lbl">No. WhatsApp</span><span class="sep">:</span>
                    <span class="val">{{ $transaksi->no_whatsapp }}</span>
                </div>
            </div>

            <hr class="divider">

            {{-- Kategori barang ini — ditampilkan besar --}}
            <div class="section-title">— Kategori Barang Ini —</div>
            <div style="display:flex; align-items:center; gap:10px; padding: 6px 2px 8px;">
                <div class="ukuran-badge">{{ $detail->ukuran }}</div>
                <div>
                    <div
                        style="font-size:8px; color:#666; letter-spacing:1px; text-transform:uppercase; margin-bottom:2px;">
                        Jenis Barang
                    </div>
                    <div style="font-size:11px; font-weight:900; color:#000; line-height:1.3;">
                        {{ implode(', ', $detail->jenis_barang ?? []) }}
                    </div>
                    <div style="font-size:8px; color:#555; margin-top:3px;">
                        Tarif: Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <hr class="divider-solid">

            {{-- Ringkasan semua barang dalam transaksi ini --}}
            <div class="section-title">— Semua Titipan ({{ $transaksi->details->count() }} kategori) —</div>
            <table class="barang-table">
                <thead>
                    <tr>
                        <th class="col-no">#</th>
                        <th style="width:60%">Nama Barang</th>
                        <th style="width:30%; text-align:right">Ukuran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->details as $j => $d)
                        <tr style="{{ $j === $i ? 'background:#f5f5f5;' : '' }}">
                            <td class="col-no">{{ $j + 1 }}</td>
                            <td style="font-weight: {{ $j === $i ? '900' : '400' }}">
                                {{ implode(', ', $d->jenis_barang ?? []) }}
                                @if ($j === $i)
                                    <span style="font-size:7px; color:#555"> ← ini</span>
                                @endif
                            </td>
                            <td style="text-align:right; color:#555; font-size:8px">{{ $d->ukuran }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="footer-barang">
                <div class="f-warning">Tempelkan pada barang titipan kategori {{ $detail->ukuran }}</div>
                <div class="f-note">
                    Jangan lepaskan sebelum barang diambil oleh penitip.<br>
                    Lembar ini digunakan untuk identifikasi saat pengambilan.
                </div>
            </div>

        </div>
    @endforeach

    {{-- Tombol Aksi --}}
    <div class="print-actions">
        <button class="btn-print" onclick="window.print()">
            &#128438; Cetak {{ $totalLembar }} Lembar
        </button>
        <button class="btn-close" onclick="window.close()">&#x2715; Tutup</button>
    </div>

</body>

</html>
