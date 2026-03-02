<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sin Fecha</title>
</head>

<style>
    * {
        margin: 0;
        padding: 0;
    }

    /* Contenedor principal centrado y en negro */
    .content {
        text-align: center;
        color: black;
    }

    /* Descripción del producto */
    .description {
        margin-top: 10px;
        margin-bottom: 5px;
        /* Reducido a la mitad (era 10px) */

        font-size: 12px;
        font-weight: bold;
    }

    /* Precio formateado */
    .price {
        font-size: 14px;
        font-weight: bold;
    }

    /* Código de barras centrado con Flexbox */
    .barcode {
        display: flex;
        justify-content: center;
        margin-top: 5px;
        margin-bottom: 0px;
    }

    /* Código numérico inferior */
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
                {!! DNS1D::getBarcodeHTML(str_pad($product->codigo, 12, '0', STR_PAD_LEFT), 'EAN13', 1, 25, 'black', true) !!}
            </div>

            <div class="code">{{ $product->codigo }}</div>
        @endforeach
    </div>

</body>

</html>