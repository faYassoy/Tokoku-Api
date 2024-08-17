<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wholesale - {{ $wholesale->wholesale_number }}</title>
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap'); */
        /* @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap'); */
        /* @import url('https://fonts.cdnfonts.com/css/fake-receipt'); */

        @page {
            /* size: 58mm 116mm; */
            margin: 0mm;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
        }

        html, body {
            /* font-family: 'Spline Sans', sans-serif; */
            /* font-family: 'Roboto', sans-serif; */
            /* font-family: 'Fake Receipt', sans-serif; */
            font-family: 'Arial', sans-serif;
            letter-spacing: 1px;
            margin: 0;
            padding: 0.4cm;
            font-size: 12px;
            font-weight: 400;
        }

        *{
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            /* font-family: 'Fake Receipt', sans-serif; */
        }
        
        table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 14px;
        }

        table th,
        table td {
        padding: 2px;
        /* background: #EEEEEE; */
        text-align: center;
        /* border-bottom: 1px solid #DDDDDD; */
        }

        table td {
            padding: 2px;
        }

        table th {
        white-space: nowrap;
        /* font-weight: normal; */
        border-bottom: 1px solid #DDDDDD;
        }

        table td, table th {
        text-align: left;
        }

        table td h3{
        font-size: 14px;
        font-weight: normal;
        }

        table .no {
        /* color: #FFFFFF; */
        color: #000;
        font-size: 16px;
        text-align: center;
        }

        table .desc {
        text-align: left;
        }

        table .total {
        background: #DDDDDD;
        }

        table td.unit,
        table td.qty,
        table td.total {
        font-size: 14px;
        }

        table tbody tr:last-child td {
        border: none;
        }

        table tfoot td {
        padding: 10px 20px;
        background: #FFFFFF;
        border-bottom: none;
        font-size: 1.2em;
        white-space: nowrap;
        /* border-top: 1px solid #AAAAAA; */
        }

        table tfoot tr:first-child td {
        border-top: none;
        }

        table tfoot tr:last-child td {
        font-size: 1.4em;

        }

        table tfoot tr td:first-child {
        border: none;
        }
        .list-content {
            justify-content: center;
            align-items: center;
        }

        .py-1{
            padding-top: 1px;
            padding-bottom: 1px;
        }

        .break-now {
            page-break-inside:avoid;
            page-break-after:always;
        }
        .pagenum:before {
            content: counter(page);
            position: absolute;
            top: 1cm;
            right: 1cm;
        }
    </style>
</head>
<body>
    <section style="padding: 0.5cm; border: 1px solid black;">
        
        <center>
            <h3>Surat Pembelian Barang</h3>
            <h3>Wahyu Abadi Group</h3>
        </center>

        <hr style="margin-top: 0.5cm;">

        <div class="list-content" style="margin-top: 0.5cm">
            <table class="table">
                <tr>
                    <td><p class="py-1">Nomor Pembelian : {{ $wholesale->wholesale_number }}</p></td>
                    <td><p class="py-1">Tanggal Cetak : {{ date('d-m-Y H:i') }}</p></td>
                </tr>
                <tr>
                    <td><p class="py-1">Gudang : {{ $wholesale->warehouse->name }}</p></td>
                    <td><p class="py-1">Tanggal : {{ date('d-m-Y H:i', strtotime($wholesale->created_at)) }}</p></td>
                </tr>
                <tr>
                    <td><p class="py-1">Supplier : {{ $wholesale->supplier->name }}</p></td>
                    <td><p class="py-1">Nomor Faktur : {{ $wholesale->no_faktur }}</p></td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 20px;margin-bottom: 14px;">
            <table class="table">
                <tr style="vertical-align: start;">
                    <th style="width: 0.5cm;">
                        No
                    </th>
                    <th>
                        Kode Barang
                    </th>
                    <th>
                        Jumlah
                    </th>
                    <th>
                        Harga
                    </th>
                    <th>
                        Total Harga
                    </th>
                </tr>
                @php
                    $i=1;
                @endphp
                @forelse ($wholesale->wholesale_products as $key =>  $wholesaleProduct)
                @if ($key > 0 && in_array($key, [11, 26, 41, 56]))
                    <tr><div style="padding-top: 1cm;"></div></tr>
                @endif
                @if ($key > 0 && (in_array($key, [10, 25, 40, 55]) || ($key + 1) == count($wholesale->wholesale_products)))
                    <span class="pagenum"></span>
                @endif
                <tr class="{{ ($key > 0 && in_array($key, [10, 25, 40, 55])) ? 'break-now' : ''}}" style="{{ ($key > 0 && in_array($key, [11, 26, 41, 56])) ? 'margin-top: 1.5cm;' : ''}}">
                    <td>{{ $i++ }}</td>
                    <td>
                        {{ $wholesaleProduct->product?->name }}
                        <p style="font-size: 10px;">({{ $wholesaleProduct->product?->brand }} / {{ $wholesaleProduct->product?->code }})</p>
                    </td>
                    <td>
                        {{ $wholesaleProduct->quantity }} Pcs
                    </td>
                    <td>
                        Rp. {{ number_format($wholesaleProduct->product_price , 0, ',', '.') }}
                    </td>
                    <td>
                        Rp. {{ number_format($wholesaleProduct->total_price , 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        <h5>Data Empty</h5>
                    </td>
                </tr>
                @endforelse
            </table>
        </div>

        {{-- <p style="margin-top: 32px;">Informasi Pembayaran : </p> --}}

        {{-- <div class="list-content" style="margin-top: 0.2cm">
            <p class="py-1">Total Harga : {{ number_format($wholesale->wholesale_products->reduce(function ($total, $wholesaleProduct) {
                return $total = $total + $wholesaleProduct->total_price;
            }) , 0, ',', '.') }}</p>
        </div> --}}
        <div class="list-content" style="margin-top: 0.2cm">
            <table class="table">
                <tr>
                    <td><p class="py-1">Total Harga : {{ number_format($wholesale->wholesale_products->reduce(function ($total, $wholesaleProduct) {
                return $total = $total + $wholesaleProduct->total_price;
            }) , 0, ',', '.') }}</p></td>
                    <td><p class="py-1">Ongkir : {{ number_format($wholesale->delivery_fee , 0, ',', '.') }}</p></td>
                    <td><p class="py-1">Pajak : {{ number_format($wholesale->tax , 0, ',', '.') }}</p></td>
                    <td><p class="py-1">Biaya Lainya : {{ number_format($wholesale->other_fee , 0, ',', '.') }}</p></td>
                    <td><p class="py-1">Grand Total : {{ number_format($wholesale->total_price , 0, ',', '.') }}</p></td>
                </tr>
            </table>
        </div>

        <table style="border: none;margin-top: 20px;">
            <td style="border: none;">
                <div class="div4" style="text-align: center;">
                    <p>Dikeluarkan oleh,</p>
                    <p style="margin-bottom: 1.7cm;"></p>
                    <p>(............................)</p>
                </div>
            </td>
            <td style="border: none;">
                <div class="div5" style="text-align: center;">
                    <p>Kepala Gudang,</p>
                    <p style="margin-bottom: 1.5cm;">
                        {{ $wholesale->warehouse->name }}
                    </p>
                    <p>(............................)</p>
                </div>
            </td>
        </table>
        
        
    </section>
</body>
</html>

