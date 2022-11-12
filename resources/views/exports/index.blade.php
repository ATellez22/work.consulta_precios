<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle</title>
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
        margin-top: 10px;
        margin-bottom: 10px;

        font-size:12px;
        font-weight:bold;
    }

    .price {
        font-size: 14px;
        font-weight: bold;
    }
    
    .barcode {
        margin-left: 60px;
        margin-top: 5px;
        margin-bottom: -10px;
    }   
    
    .code {
        font-weight: bold;
        font-size: 12px;
    }

</style>

<body>

    <div class="content">
        @foreach ($products as $product)
            <div class="description">{{ $product->descripcion }}
            </div>

            <div class="price">
                Gs {{ $precio = number_format($product->precio, 0, ',', '.') }} </div>

            <div class="barcode">
                {!! DNS1D::getBarcodeHTML($product->codigo, 'EAN13', 1, 30, 'black', true) !!}
            </div>

            <div class="code">{{ $product->codigo }}</div>
        @endforeach
    </div>

</body>

</html>
