<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Con fecha</title>
</head>

<style>
    * {
        margin: 0;
        padding: 0;
    }

    .content {
        text-align: center;
    }

    .description {
        margin-top: 5px;
        margin-bottom: 1px;
        font-size: 12px;
        font-weight: bold;
    }

    .price {
        font-size: 14px;
        font-weight: bold;
    }

    .barcode {
        margin-left: 60px;
        margin-top: 1px;
        margin-bottom: -15px;
    }

    .code {
        font-weight: bold;
        font-size: 14px;
    }

    .dates {
        margin-top: 5px;
        margin-bottom: 5px;
        font-size: 12px;
        font-weight: bold;
        text-align: left;
    }

    .date-item1 {
        margin-left: 10px;
        display: inline-block;
    }


    .date-item2 {
        margin-left: 10px;
        display: inline-block;
    }
</style>

<body>

    <div class="content">

        @foreach ($products as $product)
        <div class="description">{{ substr($product->descripcion, 0, 25) }}
        </div>

        <div class="price">
            Gs {{ $precio = number_format($product->precio, 0, ',', '.') }} </div>

        <div class="barcode">
            {!! DNS1D::getBarcodeHTML($product->codigo, 'EAN13', 1, 30, 'black', true) !!}
        </div>

        <div class="code">{{ $product->codigo }}</div>

        <div class="dates">
            <div class="date-item1">Lote: {{ $product->fecha_lote }}</div>
            <div class="date-item2">Venc: {{ $product->fecha_venc }}</div>
        </div>

        @endforeach
    </div>
</body>

</html>