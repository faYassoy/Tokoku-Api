<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delivery-{{ $delivery->delivery_number }}</title>
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap'); */
        /* @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap'); */
        @import url('https://fonts.googleapis.com/css2?family=Khand:wght@300;400;500;600;700&display=swap');
        /* @import url('https://fonts.cdnfonts.com/css/fake-receipt'); */

        @page {
            /* size: 70mm 100mm; */
            padding: 0mm;
            margin: 0mm;
        }

        html, body {
            /* font-family: 'Spline Sans', sans-serif; */
            /* font-family: 'Roboto', sans-serif; */
            font-family: 'Khand', sans-serif;
            /* font-family: 'Open Sans', sans-serif; */
            letter-spacing: 1px;
            margin: 0 3.5mm;
            padding: 0;
            font-size: 0.85rem;
            font-weight: 400;
        }

        *{
            margin: 0;
            padding: 0;
            font-family: 'Khand', sans-serif;
        }

        .border__barrier {
            width: 100%;
            border-bottom: dashed #000 1px;
            margin-top: 8px;
            margin-bottom: 8px;
        }  
        td{
            padding: 2px;
        }

        .table__menu td {
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <div style="padding: 0px; padding-top: 0;">
        <center style="">
            <h3>Surat Jalan Barang || </h3>
            <h3>Wahyu Abadi Group</h3>
        </center>

        <div class="border__barrier"></div>
        <div>
            <table>
                <tr>
                    <td style="white-space: nowrap;">No Pengiriman</td>
                    <td style="white-space: nowrap;font-size:10px;">: {{ $delivery->delivery_number }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap;">Hal </td>
                    <td style="white-space: nowrap;font-size:10px;">: Pengembalian (Rusak)</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: {{ date('d-m-Y H:i', strtotime($delivery->created_at)) }}</td>
                </tr>
                <tr>
                    <td>Waktu</td>
                    <td>: {{ date('H:i:s H:i', strtotime($delivery->created_at)) }}</td>
                </tr>
                <tr>
                    <td>Tanggal Cetak</td>
                    <td>: {{ date('d-m-Y H:i') }}</td>
                </tr>
            </table>
        </div>
        <div class="border__barrier"></div>
            <table>
                <tr>
                    <td style="white-space: nowrap">Barcode Barang</td>
                    <td style="white-space: nowrap">: {{ $delivery->delivery_products[0]->product->code ? $delivery->delivery_products[0]->product->code : "-" }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap">Barcode Barang</td>
                    <td style="white-space: nowrap">: {{ $delivery->delivery_products[0]->product->barcode }}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap">Nama Barang</td>
                    <td>: {{ $delivery->delivery_products[0]->product->name }} ({{ $delivery->delivery_products[0]->product?->brand }})</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap">Jumlah</td>
                    <td>: {{ $delivery->delivery_products[0]->quantity }} Pcs 
                        {{ $delivery->delivery_products[0]->product->carton_quantity ? "(" . $delivery->delivery_products[0]->product->carton_quantity . "Pcs/K)" : ''}}</td>
                </tr>
                <tr>
                    <td style="white-space: nowrap">Harga Jual</td>
                    <td>: Rp. {{ number_format($delivery->delivery_products[0]->product->retail_price , 0, ',', '.') }}</td>
                </tr>
            </table>
        <div class="border__barrier"></div>
        <table style="width: 100%;">
            <tr>
                <td>Total HPP</td>
                <td align="right" style="font-weight: 800; font-size: 12px;">Rp. {{ number_format($total_hpp , 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="border__barrier"></div>

        <div class="div4" style="text-align: center;margin-top:20px;">
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
        <div class="div5" style="text-align: center;margin-top:20px;">
            <p>Satpam,</p>
            <p style="margin-bottom: 1.2cm;"></p>
            <p>(............................)</p>
        </div>
        <div class="div5" style="text-align: center;margin-top:20px;">
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
    </div>
</body>
</html>
