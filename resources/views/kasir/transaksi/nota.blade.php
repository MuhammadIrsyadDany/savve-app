<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota {{ $transaksi->nomor_transaksi }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            width: 80mm;
            padding: 4mm;
            color: #000;
        }

        .center { text-align: center; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 4px 0; }

        .header { text-align: center; margin-bottom: 6px; }
        .header h2 { font-size: 16px; font-weight: bold; }
        .header p { font-size: 10px; }

        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; vertical-align: top; }
        td.label { width: 35%; }
        td.sep { width: 5%; }

        .items td { padding: 2px 0; }
        .items .item-name { font-weight: bold; }

        .total-row td { font-weight: bold; font-size: 12px; }

        .footer { text-align: center; margin-top: 8px; font-size: 10px; }

        .nomor-besar {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
            padding: 6px 0;
            border: 2px solid #000;
            margin: 6px 0;
        }

        @media print {
            body { width: 80mm; }
            .no-print { display: none; }
            @page {
                margin: 0;
                size: 80mm auto;
            }
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h2>SAVVE</h2>
        <p>Layanan Penitipan Barang</p>
    </div>
    <div class="divider"></div>

    {{-- Nomor Transaksi Besar --}}
    <div class="nomor-besar">{{ $transaksi->nomor_transaksi }}</div>

    {{-- Info Transaksi --}}
    <table>
        <tr>
            <td class="label">Event</td>
            <td class="sep">:</td>
            <td>{{ $transaksi->event->nama_event }}</td>
        </tr>
        <tr>
            <td class="label">Penitip</td>
            <td class="sep">:</td>
            <td>{{ $transaksi->nama_penitip }}</td>
        </tr>
        <tr>
            <td class="label">WhatsApp</td>
            <td class="sep">:</td>
            <td>{{ $transaksi->no_whatsapp }}</td>
        </tr>
        <tr>
            <td class="label">Kasir</td>
            <td class="sep">:</td>
            <td>{{ $transaksi->kasir->name }}</td>
        </tr>
        <tr>
            <td class="label">Waktu</td>
            <td class="sep">:</td>
            <td>{{ $transaksi->waktu_penitipan->format('d/m/Y H:i') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- Detail Barang --}}
    <table class="items">
        <tr>
            <td colspan="3" class="bold">DAFTAR BARANG:</td>
        </tr>
        @foreach($transaksi->details as $i => $detail)
        <tr>
            <td colspan="3" class="item-name">
                {{ $i + 1 }}. {{ $detail->nama_barang_custom ?? $detail->kategori->nama_kategori }}
            </td>
        </tr>
        <tr>
            <td style="padding-left:10px">Ukuran: {{ $detail->ukuran }}</td>
            <td>Qty: {{ $detail->jumlah }}</td>
            <td style="text-align:right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </table>

    <div class="divider"></div>

    {{-- Total --}}
    <table>
        <tr class="total-row">
            <td>TOTAL</td>
            <td style="text-align:right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- Footer --}}
    <div class="footer">
        <p>Tunjukkan nota ini saat pengambilan</p>
        <p>Barang tanpa nota tidak akan diserahkan</p>
        <br>
        <p>Terima kasih!</p>
    </div>

    {{-- Tombol Print (tidak ikut tercetak) --}}
    <div class="no-print" style="margin-top: 16px; text-align:center">
        <button onclick="window.print()"
            style="padding: 8px 24px; background:#16a34a; color:white; border:none; border-radius:6px; cursor:pointer; font-size:13px">
            🖨️ Cetak Nota
        </button>
        <button onclick="window.close()"
            style="padding: 8px 24px; background:#6b7280; color:white; border:none; border-radius:6px; cursor:pointer; font-size:13px; margin-left:8px">
            ✕ Tutup
        </button>
    </div>

</body>
</html>