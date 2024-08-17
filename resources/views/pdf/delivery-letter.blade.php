{{-- @php
    $type_delivery = [
        "procurement" => "Pengiriman Ke Toko",
        "return" => "Pengembalian Dari Toko",
        "request" => "Permintaan Dari Toko",
        "to_supplier" => "Pengembalian Ke Supplier"
        "sale" => "Pengiriman Ke Customer"
    ];
@endphp --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delivery - {{ $delivery->delivery_number }}</title>
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap'); */
        /* @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap'); */
        /* @import url('https://fonts.cdnfonts.com/css/fake-receipt'); */
        /* @import url('https://fonts.googleapis.com/css2?family=Khand:wght@300;400;500;600;700&display=swap'); */

        @page {
            /* size: 58mm 116mm; */
            margin: 0mm;
            padding: 0;
        }

        body {
            /* font-family: 'Open Sans', sans-serif; */
            /* font-family: 'Khand', sans-serif; */
            font-family: 'Arial', sans-serif;
        }

        html, body {
            /* font-family: 'Spline Sans', sans-serif; */
            /* font-family: 'Roboto', sans-serif; */
            font-family: 'Arial', sans-serif;
            letter-spacing: 1px;
            margin: 0;
            padding: 0.4cm;
            font-size: 11px;
            font-weight: 400;
        }

        *{
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        
        table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px;
        }

        table th,
        table td {
        padding: 3px;
        /* background: #EEEEEE; */
        text-align: center;
        
        }

        table td {
            padding: 2px;
        }

        table th {
        white-space: nowrap;
        /* font-weight: normal; */
        padding-bottom: 0.2cm;
        border-bottom: 1px solid #4e4e4e;
        }

        table td, table th {
        text-align: left;
        }

        table td h3{
        font-size: 12px;
        font-weight: normal;
        }

        table .no {
        /* color: #FFFFFF; */
        color: #000;
        font-size: 14px;
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
        font-size: 12px;
        }

        table tbody tr:last-child td {
        border: none;
        }

        table tfoot td {
        padding: 2px 2px;
        background: #FFFFFF;
        border-bottom: none;
        font-size: 11px;
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
    <section style="padding: 0.5cm;">
        
        <center>
            <h3>Surat Jalan Barang || Wahyu Abadi Group</h3>
        </center>

        <hr style="margin-top: 0.25cm;">

        <div class="list-content" style="margin-top: 0.2cm">
            <table>
                <tr>
                    {{-- @php
    $type_delivery = [
        "procurement" => "Pengiriman Ke Toko",
        "return" => "Pengembalian Dari Toko",
        "request" => "Permintaan Dari Toko",
        "to_supplier" => "Pengembalian Ke Supplier"
        "sale" => "Pengiriman Ke Customer"
    ];
@endphp --}}
                    <td><p class="py-1" style="font-size: 11px;">Nomor Pengiriman : {{ $delivery->delivery_number }}</p></td>
                    <td><p class="py-1" style="font-size: 11px;">Hal : {{$delivery->type == 'procurement' ? 'Pengiriman Ke Toko' : ($delivery->type == 'return' ? ($delivery->is_broke ? 'Pengembalian Dari Toko (Rusak)' :  'Pengembalian Dari Toko') : ($delivery->type == 'request' ? 'Permintaan Dari Toko' :  ($delivery->type == 'to_supplier' ? "Pengembalian Ke Supplier" : ($delivery->type == 'store_to_store' ? "Dari Toko ke Toko" : "Pengiriman Ke Customer"))))  }}</p></td>
                </tr>
                <tr>
                    <td><p class="py-1" style="font-size: 11px;">Tanggal : {{ date('d-m-Y H:i', strtotime($delivery->created_at)) }}</p></td>
                    <td><p class="py-1" style="font-size: 11px;">Tanggal Cetak : {{ date('d-m-Y H:i') }}</p></td>
                </tr>
                <tr>
                    <td>
                        <p class="py-1" style="font-size: 11px;">
                            Tujuan : @if($delivery->warehouse_receiver_id) {{ $delivery->warehouse_receiver->name }}
                                    @elseif($delivery->store_receiver_id) {{ $delivery->store_receiver->name }}
                                    @elseif($delivery->supplier_receiver_id) {{ $delivery->supplier_receiver->name }}
                                    @endif
                        </p>
                    </td>
                    @if($delivery->type != 'sale')
                        <td>
                            <p class="py-1" style="font-size: 12px;">
                                Total HPP : Rp. {{ number_format($total_hpp , 0, ',', '.') }}
                            </p>
                        </td>
                    @endif
                </tr>
            </table>
        </div>

        {{-- <p style="margin-top: 12px;">Mengirimkan barang berikut : </p> --}}

        <div style="margin-top: 12px;margin-bottom: 12px;">
            <table class="table">
                <tr style="vertical-align: start;">
                    <th style="width: 0.5cm;">
                        No
                    </th>
                    <th>
                        Kode / Barcode Barang
                    </th>
                    <th>
                        Keterangan Barang
                    </th>
                    <th>
                        Jumlah
                    </th>
                    <th>
                        Harga Jual
                    </th>
                </tr>
                @php
                    $i=1;
                @endphp
                @foreach ($delivery->delivery_products as $key => $deliveryProduct)
                @if ($key > 0 && in_array($key, [12, 27, 42, 57]))
                    <tr><div style="padding-top: 1cm;"></div></tr>
                @endif
                @if ($key > 0 && (in_array($key, [11, 26, 41, 56]) || ($key + 1) == count($delivery->delivery_products)))
                    <span class="pagenum"></span>
                @endif
                <tr class="{{ ($key > 0 && in_array($key, [11, 26, 41, 56])) ? 'break-now' : ''}}" style="{{ ($key > 0 && in_array($key, [12, 27, 42, 57])) ? 'margin-top: 1.5cm;' : ''}}">
                    <td style="{{ ($key < 1) ? 'padding-top: 0.2cm;' : ''}}">{{ $i++ }}</td>
                    <td style="{{ ($key < 1) ? 'padding-top: 0.2cm;' : ''}}">
                        {{ $deliveryProduct->product->code ? $deliveryProduct->product->code : "-" }} / {{ $deliveryProduct->product->barcode }}
                    </td>
                    <td style="{{ ($key < 1) ? 'padding-top: 0.2cm;' : ''}}">
                        {{ $deliveryProduct->product->name }} ({{ $deliveryProduct->product?->brand }})
                    </td>
                    <td style="{{ ($key < 1) ? 'padding-top: 0.2cm;' : ''}}">
                        {{ $deliveryProduct->quantity }} Pcs 
                        {{ $deliveryProduct->product->carton_quantity ? "(" . $deliveryProduct->product->carton_quantity . "Pcs/K)" : ''}}
                    </td>
                    <td style="{{ ($key < 1) ? 'padding-top: 0.2cm;' : ''}}">
                        Rp. {{ number_format($deliveryProduct->product->retail_price , 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
                {{-- @empty
                <tr>
                    <td colspan="7" class="text-center">
                        <h5>Data Empty</h5>
                    </td>
                </tr>
                @endforelse --}}
            </table>
        </div>
        {{-- <div class="break-now"></div> --}}

        <table style="border: none;">
            <td style="border: none;">
                <div class="div4" style="text-align: center;">
                    <p>Dikeluarkan oleh,</p>
                    
                    <p style="margin-bottom: 1cm;">
                        @if($delivery->warehouse_sender_id)
                            {{ $delivery->warehouse_sender->name }}
                        @elseif($delivery->store_sender_id)
                            {{ $delivery->store_sender->name }}
                        @elseif($delivery->supplier_sender_id)
                            {{ $delivery->supplier_sender->name }}
                        @endif
                    </p>
                    <p>(............................)</p>
                </div>
            </td>
            <td style="border: none;">
                <div class="div5" style="text-align: center;">
                    <p>Satpam,</p>
                    <p style="margin-bottom: 1.2cm;"></p>
                    <p>(............................)</p>
                </div>
            </td>
            <td style="border: none;">
                <div class="div5" style="text-align: center;">
                    <p>Diterima oleh,</p>
                    <p style="margin-bottom: 1cm;">
                        @if($delivery->warehouse_receiver_id)
                            {{ $delivery->warehouse_receiver->name }}
                        @elseif($delivery->store_receiver_id)
                            {{ $delivery->store_receiver->name }}
                        @elseif($delivery->supplier_receiver_id)
                            {{ $delivery->supplier_receiver->name }}
                        @endif
                    </p>
                    <p>(............................)</p>
                </div>
            </td>
        </table>
    </section>
</body>
</html>

