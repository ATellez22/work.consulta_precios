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

    /* Contenedor principal centrado y en negro */
    .content {
        text-align: center;
        color: black;
    }

    /* Descripción del producto (limitada a 25 caracteres) */
    .description {
        margin-top: 5px;
        margin-bottom: 0px;
        /* Reducido (era 1px) */
        font-size: 12px;
        font-weight: bold;
    }

    /* Precio formateado */
    .price {
        font-size: 14px;
        font-weight: bold;
    }

    /* Código de barras centrado */
    .barcode {
        display: flex;
        justify-content: center;
        margin-top: 1px;
        margin-bottom: 0px;
    }

    /* Código numérico inferior */
    .code {
        font-weight: bold;
        font-size: 14px;
    }

    /* Contenedor de fechas de lote y vencimiento */
    .dates {
        margin-top: 5px;
        margin-bottom: 5px;
        font-size: 12px;
        font-weight: bold;
        text-align: left;
    }

    /* Item individual para la fecha de lote */
    .date-item1 {
        margin-left: 10px;
        display: inline-block;
    }


    /* Item individual para la fecha de vencimiento */
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
                {!! DNS1D::getBarcodeHTML(str_pad($product->codigo, 12, '0', STR_PAD_LEFT), 'EAN13', 1, 25, 'black', true) !!}
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