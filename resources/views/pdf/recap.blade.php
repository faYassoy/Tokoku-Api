<!DOCTYPE html>
<html lang="en">
<head>
    <title>Rekap Penjualan {{ $type }} - {{ $filter }}</title>
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
            font-family: 'Open Sans', sans-serif;
        }

        html, body {
            /* font-family: 'Spline Sans', sans-serif; */
            /* font-family: 'Roboto', sans-serif; */
            /* font-family: 'Fake Receipt', sans-serif; */
            letter-spacing: 1px;
            margin: 0;
            padding: 1.5cm;
            font-size: 12px;
            font-weight: 450;
        }

        *{
            margin: 0;
            padding: 0;
            /* font-family: 'Fake Receipt', sans-serif; */
        }
        
        table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        }

        table th,
        table td {
        padding: 14px;
        /* background: #EEEEEE; */
        text-align: center;
        border-bottom: 1px solid #DDDDDD;
        }

        table td {
            padding: 12px;
        }

        table th {
        white-space: nowrap;
        font-weight: normal;
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

        table .unit {
        /* background: #DDDDDD; */
        }

        table .qty {
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
        border-top: 1px solid #AAAAAA;
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
            padding-top: 3px;
            padding-bottom: 3px;
        }
    </style>
</head>
<body>
    <section style="padding: 0.5cm; border: 1px solid black;">
        
        <center>
            <h3>Rekap Penjualan {{ $type }} {{ $filter }} </h3>
            <h3>Wahyu Abadi Group</h3>
        </center>

        <hr style="margin-top: 0.5cm;">

        <div class="list-content" style="margin-top: 0.5cm">
            <p class="py-1">Tanggal/Minggu/Bulan/Tahun : {{ $filter }}</p>
            <p class="py-1">Tanggal Cetak : {{ date('d-m-Y') }}</p>
        </div>

        <p style="margin-top: 32px;">Hasil Penjualan : </p>

        <div style="margin-top: 12px;margin-bottom: 32px;">
            <table class="table">
                <tr style="vertical-align: start;">
                    <th style="width: 0.5cm;">
                        No
                    </th>
                    <th>
                        {{ $type }}
                    </th>
                    <th>
                        Produk Terjual
                    </th>
                    <th>
                        Nilai Penjualan
                    </th>
                    <th>
                        Nilai HPP <br> Terjual
                    </th>
                    <th>
                        Laba
                    </th>

                    @if ($type == 'Toko')
                        <th>Pengeluaran</th>
                        <th>Uang Masuk</th>
                        <th>Uang Masuk <br> (Non Tunai)</th>
                    @endif
                </tr>
                @php
                    $i=1;
                    $total_income = 0;
                    $total_hpp = 0;
                    $total_laba = 0;
                @endphp
                @forelse ($sales as $sale)
                @if($sale->name) 
                <tr>
                    <td>{{ $i++ }}</td>
                    <td>
                        {{ $sale->name }}
                    </td>
                    <td>
                        {{ $sale->total_sale_product }} Pcs
                    </td>
                    <td>
                        Rp. {{ number_format($sale->total_income , 0, ',', '.') }}
                    </td>
                    <td>
                        Rp. {{ number_format($sale->total_hpp , 0, ',', '.') }}
                    </td>
                    <td>
                        Rp. {{ number_format($sale->total_income - $sale->total_hpp , 0, ',', '.') }}
                    </td>

                    @if ($type == 'Toko')
                        <td>
                            Rp. {{ number_format($sale->total_out_income , 0, ',', '.') }}
                        </td>
                        <td>
                            Rp. {{ number_format($sale->total_payment , 0, ',', '.') }}
                        </td>
                        <td>
                            Rp. {{ number_format($sale->total_payment_transfer , 0, ',', '.') }}
                        </td>
                    @endif
                </tr>
                @php
                    $total_income += $sale->total_income;
                    $total_hpp += $sale->total_hpp;
                    $total_laba += $sale->total_income - $sale->total_hpp;
                @endphp
                @endif
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        <h5>Data Empty</h5>
                    </td>
                </tr>
                @endforelse
            </table>

            <div class="list-content" style="margin-top: 0.5cm">
                <p class="py-1">Total Nilai Penjualan : {{ number_format($total_income , 0, ',', '.') }}</p>
                <p class="py-1">Total HPP Terjual : {{ number_format($total_hpp , 0, ',', '.') }}</p>
                <p class="py-1">Total Laba : {{ number_format($total_laba , 0, ',', '.') }}</p>
            </div>
        </div>

        <table style="border: none;">
            <td style="border: none;">
                <div class="div4" style="text-align: center;">
                    <p>Mengetahui,</p>
                    <p style="margin-bottom: 1.7cm;"></p>
                    <p>(............................)</p>
                </div>
            </td>
        </table>
        
        
    </section>
</body>
</html>

