@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;

    // Susun urutan halaman: 1 nota + N label kategori barang
    $halaman = collect([['tipe' => 'nota']])->concat(
        $transaksi->details->map(fn($detail) => ['tipe' => 'label', 'detail' => $detail]),
    );
    $totalLembar = $halaman->count();
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota {{ $transaksi->nomor_transaksi }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        :root {
            --lebar-kertas: 58mm;
            /* lebar fisik paper roll thermal */
            --pad-x: 3.5mm;
            --pad-y: 3mm;
            --pad-x-print: 2.5mm;
            --pad-y-print: 2mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 9px;
            line-height: 1.4;
            background: #ddd;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 28px 16px 48px;
            min-height: 100vh;
        }

        /* ===== Wrapper & separator ===== */
        .nota-wrapper {
            width: var(--lebar-kertas);
            background: #fff;
            padding: var(--pad-y) var(--pad-x);
            box-shadow: 0 3px 14px rgba(0, 0, 0, 0.18);
        }

        .nota-separator {
            width: var(--lebar-kertas);
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 0;
            color: #aaa;
            font-size: 7px;
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

        /* ===== Title bar tiap lembar ===== */
        .doc-title-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1.5px solid #000;
            padding: 3px 5px;
            margin-bottom: 5px;
        }

        .doc-title-bar .doc-title {
            font-size: 7.5px;
            font-weight: 900;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .doc-title-bar .doc-lembar {
            font-size: 6.5px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #555;
            border-left: 1px solid #ccc;
            padding-left: 5px;
        }

        /* ===== Header logo + brand ===== */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 4px;
            margin-bottom: 4px;
            border-bottom: 2px solid #000;
            gap: 5px;
        }

        .header img {
            height: 24px;
            width: auto;
            object-fit: contain;
            filter: grayscale(100%) contrast(160%);
        }

        .header-right {
            text-align: right;
        }

        .header-right .brand {
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            line-height: 1;
        }

        .header-right .tagline {
            font-size: 5.5px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #666;
            margin-top: 2px;
        }

        /* ===== Identitas penitip + QR ===== */
        .identity-block {
            display: flex;
            align-items: stretch;
            border: 1.5px solid #000;
            margin-bottom: 5px;
        }

        .identity-left {
            flex: 1;
            min-width: 0;
            padding: 4px 5px;
            border-right: 1.5px solid #000;
        }

        .identity-left .lbl {
            font-size: 6px;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 1px;
        }

        .identity-left .nama {
            font-size: 13px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            line-height: 1.1;
            word-break: break-word;
        }

        .identity-left .kode-wrap {
            margin-top: 4px;
            padding-top: 3px;
            border-top: 1px dashed #bbb;
        }

        .identity-left .kode {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .identity-right {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4px;
            gap: 2px;
        }

        .identity-right svg {
            display: block;
            width: 40px !important;
            height: 40px !important;
        }

        .identity-right .qr-label {
            font-size: 5px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            color: #666;
            text-align: center;
            line-height: 1.2;
        }

        /* ===== Badge ukuran (lembar label) ===== */
        .ukuran-badge {
            display: inline-block;
            border: 2px solid #000;
            font-size: 22px;
            font-weight: 900;
            width: 34px;
            height: 34px;
            line-height: 30px;
            text-align: center;
            margin-bottom: 3px;
        }

        .kategori-row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 1px 8px;
        }

        .kategori-row .info-jenis-label {
            font-size: 7px;
            color: #666;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .kategori-row .info-jenis-nilai {
            font-size: 10px;
            font-weight: 900;
            line-height: 1.3;
        }

        .kategori-row .info-jenis-tarif {
            font-size: 7px;
            color: #555;
            margin-top: 3px;
        }

        /* ===== Garis & judul section ===== */
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

        .section-title {
            font-size: 6.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            text-align: center;
            margin: 4px 0;
        }

        /* ===== Info baris (event, waktu, dst) ===== */
        .info-section {
            margin-bottom: 4px;
        }

        .info-row {
            display: flex;
            align-items: baseline;
            padding: 1px 0;
            font-size: 8px;
            gap: 2px;
        }

        .info-row .lbl {
            color: #555;
            width: 38%;
            flex-shrink: 0;
        }

        .info-row .sep {
            color: #999;
            flex-shrink: 0;
        }

        .info-row .val {
            font-weight: 700;
            flex: 1;
            text-align: right;
        }

        /* ===== Tabel daftar barang ===== */
        .barang-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-bottom: 2px;
        }

        .barang-table thead th {
            font-size: 6.5px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #555;
            padding: 2px 1px 3px;
            border-bottom: 1px solid #000;
            text-align: left;
        }

        .barang-table thead th:last-child {
            text-align: right;
        }

        .barang-table tbody td {
            padding: 3px 1px;
            vertical-align: top;
            border-bottom: 1px dotted #ccc;
        }

        .barang-table tbody tr:last-child td {
            border-bottom: none;
        }

        .barang-table .col-no {
            width: 8%;
            color: #555;
        }

        .barang-table .col-nama {
            width: 48%;
            font-weight: 700;
        }

        .barang-table .col-detail {
            width: 20%;
            color: #555;
            font-size: 7px;
        }

        .barang-table .col-harga {
            width: 24%;
            font-weight: 700;
            text-align: right;
        }

        /* ===== Total pembayaran ===== */
        .total-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1.5px solid #000;
            padding: 4px 5px;
            margin: 5px 0;
        }

        .total-box .total-label {
            font-size: 7px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .total-box .total-value {
            font-size: 11px;
            font-weight: 900;
        }

        /* ===== Footer nota & label ===== */
        .footer,
        .footer-barang {
            text-align: center;
            margin-top: 6px;
            padding-top: 5px;
        }

        .footer {
            border-top: 1.5px solid #000;
        }

        .footer-barang {
            border-top: 1px dashed #000;
        }

        .footer .f-warning,
        .footer-barang .f-warning {
            font-size: 7px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .footer .f-note,
        .footer-barang .f-note {
            font-size: 6.5px;
            color: #444;
            line-height: 1.5;
        }

        .footer .f-thanks {
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-top: 5px;
        }

        .footer .f-copy {
            font-size: 6px;
            color: #999;
            margin-top: 2px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* ===== Tombol aksi (tidak ikut tercetak) ===== */
        .print-actions {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: var(--lebar-kertas);
            min-width: 220px;
        }

        .print-actions-row {
            display: flex;
            gap: 8px;
        }

        .btn-print,
        .btn-close {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            font-family: sans-serif;
            letter-spacing: 0.5px;
            transition: opacity .2s, background .15s;
        }

        .btn-print {
            background: #000;
            color: #fff;
            border: none;
        }

        .btn-print:hover {
            opacity: .8;
        }

        .btn-close {
            background: #fff;
            color: #000;
            border: 2px solid #000;
        }

        .btn-close:hover {
            background: #f0f0f0;
        }

        .print-hint {
            font-size: 10.5px;
            color: #666;
            text-align: center;
            font-family: sans-serif;
        }

        @media print {
            body {
                background: #fff !important;
                padding: 0;
                align-items: center;
            }

            .nota-wrapper {
                box-shadow: none;
                width: var(--lebar-kertas);
                padding: var(--pad-y-print) var(--pad-x-print);
                page-break-after: always;
                break-after: page;
            }

            .nota-wrapper:last-of-type {
                page-break-after: avoid;
                break-after: avoid;
            }

            .nota-separator {
                color: #000 !important;
                padding: 6px 0;
            }

            .nota-separator::before,
            .nota-separator::after {
                border-top: 1px dashed #000 !important;
            }

            .print-actions {
                display: none !important;
            }

            * {
                background: #fff !important;
                color: #000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    @foreach ($halaman as $h)
        @if ($h['tipe'] === 'label')
            <div class="nota-separator">
                &#8212; potong di sini &nbsp;&middot;&nbsp; lembar {{ $loop->iteration }} ditempel pada barang &#8212;
            </div>
        @endif

        <div class="nota-wrapper">
            <div class="doc-title-bar">
                <span class="doc-title">
                    {{ $h['tipe'] === 'nota' ? 'Bukti Transaksi Penitipan' : 'Label Identifikasi Barang' }}
                </span>
                <span class="doc-lembar">Lembar {{ $loop->iteration }} / {{ $totalLembar }}</span>
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
                    {!! QrCode::size(40)->margin(1)->errorCorrection('H')->style('square')->generate($transaksi->nomor_transaksi) !!}
                    <div class="qr-label">Scan saat<br>pengambilan</div>
                </div>
            </div>

            @if ($h['tipe'] === 'nota')
                <div class="info-section">
                    <div class="info-row"><span class="lbl">Event</span><span class="sep">:</span><span
                            class="val">{{ $transaksi->event->nama_event }}</span></div>
                    <div class="info-row"><span class="lbl">Waktu Penitipan</span><span class="sep">:</span><span
                            class="val">{{ $transaksi->waktu_penitipan->format('d/m/Y H:i') }}</span></div>
                    <div class="info-row"><span class="lbl">No. WhatsApp</span><span class="sep">:</span><span
                            class="val">{{ $transaksi->no_whatsapp }}</span></div>
                    <div class="info-row"><span class="lbl">Metode Bayar</span><span class="sep">:</span><span
                            class="val">{{ $transaksi->metode_bayar }}</span></div>
                    <div class="info-row"><span class="lbl">Kasir</span><span class="sep">:</span><span
                            class="val">{{ $transaksi->kasir->name }}</span></div>
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

                <div class="footer">
                    <div class="f-warning">Simpan nota ini sebagai bukti transaksi</div>
                    <div class="f-note">
                        Tunjukkan nota atau scan QR saat pengambilan barang.<br>
                        Barang tidak akan diserahkan tanpa bukti transaksi ini.
                    </div>
                    <div class="f-thanks">Terima Kasih</div>
                    <div class="f-copy">© {{ date('Y') }} Vendor Savve</div>
                </div>
            @else
                <div class="info-section">
                    <div class="info-row"><span class="lbl">Event</span><span class="sep">:</span><span
                            class="val">{{ $transaksi->event->nama_event }}</span></div>
                    <div class="info-row"><span class="lbl">Waktu Penitipan</span><span class="sep">:</span><span
                            class="val">{{ $transaksi->waktu_penitipan->format('d/m/Y H:i') }}</span></div>
                    <div class="info-row"><span class="lbl">No. WhatsApp</span><span class="sep">:</span><span
                            class="val">{{ $transaksi->no_whatsapp }}</span></div>
                </div>

                <hr class="divider">

                <div class="section-title">— Kategori Barang Ini —</div>
                <div class="kategori-row">
                    <div class="ukuran-badge">{{ $h['detail']->ukuran }}</div>
                    <div>
                        <div class="info-jenis-label">Jenis Barang</div>
                        <div class="info-jenis-nilai">{{ implode(', ', $h['detail']->jenis_barang ?? []) }}</div>
                        <div class="info-jenis-tarif">Tarif: Rp
                            {{ number_format($h['detail']->subtotal, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="footer-barang">
                    <div class="f-warning">Tempelkan pada barang titipan kategori {{ $h['detail']->ukuran }}</div>
                    <div class="f-note">
                        Jangan lepaskan sebelum barang diambil oleh penitip.<br>
                        Lembar ini digunakan untuk identifikasi saat pengambilan.
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    <div class="print-actions">
        <div class="print-actions-row">
            <button id="btnCetakPdf" class="btn-print" type="button">🖶 Cetak {{ $totalLembar }} Lembar</button>
            <button class="btn-close" type="button" onclick="window.close()">✕ Tutup</button>
        </div>
        <div class="print-hint">Tips: nonaktifkan opsi “Headers and footers” pada dialog print agar ukuran hasil cetak
            tetap pas.</div>
    </div>

    <script>
        function pxToMm(px) {
            return px / 3.7795275591; // konversi px (96dpi) ke mm
        }

        document.getElementById('btnCetakPdf').addEventListener('click', function() {
            const wrappers = document.querySelectorAll('.nota-wrapper');
            if (wrappers.length === 0) return;

            const LEBAR_MM = 58;
            const BUFFER_MM = 5; // dinaikkan dari 3mm jadi 5mm untuk jaga-jaga ekstra

            const tinggiNota = pxToMm(wrappers[0].offsetHeight) + BUFFER_MM;

            let tinggiLabel = tinggiNota;
            if (wrappers.length > 1) {
                tinggiLabel = 0;
                for (let i = 1; i < wrappers.length; i++) {
                    // ikut hitung tinggi nota-separator yang ada SEBELUM wrapper ini
                    const separator = wrappers[i].previousElementSibling;
                    const tinggiSep = (separator && separator.classList.contains('nota-separator')) ?
                        pxToMm(separator.offsetHeight) :
                        0;

                    const totalTinggi = pxToMm(wrappers[i].offsetHeight) + tinggiSep + BUFFER_MM;
                    tinggiLabel = Math.max(tinggiLabel, totalTinggi);
                }
            }

            const style = document.createElement('style');
            style.textContent = `
            @media print {
                @page :first { margin: 0; size: ${LEBAR_MM}mm ${tinggiNota.toFixed(1)}mm; }
                @page { margin: 0; size: ${LEBAR_MM}mm ${tinggiLabel.toFixed(1)}mm; }
            }
        `;
            document.head.appendChild(style);
            window.print();
            document.head.removeChild(style);
        });
    </script>
</body>

</html>
