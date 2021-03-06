<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            size: {{ $options['width'] ?? 35 }}mm {{ $options['height'] ?? 24 }}mm;
            margin: {{ $options['margin'] ?? 1 }}mm;
        }

        body {
            position: relative;
            font-family: DejaVu Sans, sans-serif;
            font-size: {{ $options['fontSize'] ?? 9 }}px !important;
            line-height: 2mm;
            page-break-inside: avoid;
        }

        body > div {
            position: absolute;
        }

        #company-title {
            height: 3mm;
            font-weight: bold
        }

        #product-price {
            right: 0;
        }

        #product-title {
            top: 3mm;
            width: 100%;
            height: {{ ($options['height'] ?? 24) - 20 }}mm;
            overflow: hidden;
            line-height: 2mm;
        }

        #barcode {
            bottom: 5mm;
            left: 0;
            right: 0;
            text-align: center;
            height: 9mm;
        }

        #barcode img {
            width: 100%;
            height: 100%
        }

        #barcode-numbers {
            bottom: 1mm;
            width: 100%;
            text-align: center
        }

        .page-break {
            page-break-after: always;
        }
    </style>
    <title>Ετικέτες {{ $options['width'] ?? 35 }}mm x {{ $options['height'] ?? 24 }}mm</title>
</head>

<body>
@for($i=0; $i<($options['copies'] ?? 1); $i++)
    @foreach($products as $product)
        @for($j=0; $j<$product->labels_count; $j++)
            <div id="company-title">{{ config('app.name') }}</div>
            <div id="product-price">{{ format_number($product->price, 2) }}</div>

            <div id="product-title">{{ $product->trademark }}</div>

            @if($product->barcode !== null)
                <div id="barcode">
                    <img src="data:image/svg;base64,{{ $product->barcode_img }}" alt="">
                </div>

                <div id="barcode-numbers">{{ $product->barcode }}</div>
            @endif

            @if($j < $product->labels_count - 1)
                <div class="page-break"></div>
            @endif
        @endfor

        @if(!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

    @if($i < ($options['copies'] ?? 1) - 1)
        <div class="page-break"></div>
    @endif
@endfor
</body>
</html>
