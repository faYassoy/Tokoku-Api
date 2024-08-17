<!DOCTYPE html>
<html lang="en">
<head>
    <title>Print Many Barcode</title>
    <style>
        /* @import url('https://fonts.googleapis.com/css2?family=Spline+Sans:wght@300;400;500;600;700&display=swap'); */
        /* @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap'); */
        /* @import url('https://fonts.cdnfonts.com/css/fake-receipt'); */
        @import url('https://fonts.googleapis.com/css2?family=Khand:wght@300;400;500;600;700&display=swap');

        @page {
            /* size: 108mm 400mm; */
            margin: 0mm;
            padding: 0;
        }

        html, body {
            /* font-family: 'Spline Sans', sans-serif; */
            /* font-family: 'Roboto', sans-serif; */
            /* font-family: 'Fake Receipt', sans-serif; */
            font-family: 'Khand', sans-serif;
            letter-spacing: 1px;
            margin: 0;
            padding: 2mm;
            font-size: 0.85rem;
            font-weight: 450;
        }

        *{
            margin: 0;
            padding: 0;
            font-family: 'Khand', sans-serif;
        }

        div .break-now {
            page-break-inside:avoid;
            page-break-after:always;
        }
        
        table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        }

        table th,
        table td {
        padding: 1mm;
        /* background: #EEEEEE; */
        text-align: center;
        border-bottom: 1px solid #DDDDDD;
        }

        table td {
            padding: 1mm;
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

        .py-1{
            padding-top: 3px;
            padding-bottom: 3px;
        }
    </style>
</head>
<body>
    <table style="border: none;">
        @foreach ($data as $key => $row)
            @if ($key%8 == 0)
                {{ '<div class="break-now"></div>' }}
            @endif
            <tr>
                {{-- @for ($i=0; $i<=$product->print_amount; $i++) --}}
                @foreach ($row as $product)
                    <td style="border: none;">
                        <div style="max-width: 38mm; min-width: 38mm; min-height: 17mm; max-height: 17mm; padding-top: 1mm; padding-bottom: 1mm; padding-left: 2mm; padding-right: 2mm; position:relative;">
                            @if($withName)
                                <p style="font-weight: 400;font-size: 9px;margin-bottom: 1px;">{{ $product->name }} <span style="font-size: 7px;">({{ $product->code ? $product->code : '-' }})</span></p>
                            @endif
                            <img src={{"data:image/png;base64," . DNS1D::getBarcodePNG($product->barcode, "C39",1.1,20,array(1,1,1), false)}} alt="barcode" style="width: {{ $withStore ? '92%' : '100%' }}; {{($withName && $withPrice) ? 'height: 25px;' : (($withName || $withPrice) ? 'height: 35px; margin-top: 2px;' : 'height: 40px; margin-top: 2px;')}}" />
                            <p style="font-weight: 400;font-size: 8px;margin-bottom: 1px;margin-top: 1px;text-align: center;">{{ $product->barcode }}</p>
                            @if($withPrice)
                                <p style="font-weight: 500;font-size: 12px;">Rp {{ number_format($product->retail_price, 0, ',', '.') }} <span style="font-weight: 400; font-size: 8px;">({{ $product->distributor_price_code }})</span></p>
                            @endif
                            @if($withStore)
                                <p style="font-weight: 400;font-size: 8px;position: absolute; top: 5%; right: -44.5%; transform: rotate(90deg);width: 100%; text-align: center;">{{$withStore}}</p>
                            @endif
                        </div>
                    </td>
                {{-- @endfor --}}
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>

