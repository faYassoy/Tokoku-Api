<!DOCTYPE html>
<html lang="en">
<head>
    <title>Transaksi-{{ $sale->sale_number }}</title>
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
<<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'sans-serif';
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 0;
        }
        .details, .transaction-details {
            width: 100%;
            margin-bottom: 10px;
        }
        .details td, .transaction-details td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        .details {
            margin-bottom: 20px;
        }
        .transaction-details th {
            background-color: #f7f7f7;
            text-align: left;
        }
        .transaction-details td {
            text-align: right;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Receipt</h1>
        <p>Transaction No: {{ $sale->transaction_number }}</p>
        <p>Date: {{ $date ?? $sale->transaction_date}} {{ $time ?? $sale->transaction_date}}</p>
    </div>

    <table class="details">
        <tr>
            <td><strong>Customer:</strong></td>
            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td><strong>Payment Type:</strong></td>
            <td>{{ $sale->payment_type }}</td>
        </tr>
        <tr>
            <td><strong>Total Payment:</strong></td>
            <td>{{ $sale->total_payment }}</td>
        </tr>
    </table>

    <table class="transaction-details">
        <thead>
        <tr>
            <th>Barang</th>
            <th>Jml</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sale->transactionDetails as $product)
            <tr>
                <td>{{ $product->product->name }}</td>
                <td>{{ $product->quantity }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->total_price }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
{{-- 
    <table class="details">
        <tr>
            <td><strong>Total BP:</strong></td>
            <td>{{ $sale->total_bp }}</td>
        </tr>
        <tr>
            <td><strong>Total Price:</strong></td>
            <td>{{ $sale->total_price }}</td>
        </tr>
    </table> --}}

    <div class="footer">
        <p>Terima Kasih!</p>
    </div>
</div>
</body>
</html>

</html>
