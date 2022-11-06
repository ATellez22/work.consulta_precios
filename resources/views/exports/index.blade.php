<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detalle</title>
</head>
<body>
   
    <div>
        @foreach ($products as $product)

            <h1 style="font-weight: bold; font-size: 30px; margin:auto;">{{ $product->descripcion }}</h1>
            <h1 style="font-weight: bold; font-size: 40px; margin:auto; margin-left:60px;">
                Gs {{ $precio = number_format( $product->precio, 0 ,',' ,'.' ); }} </h1>

            <div> {!! DNS1D::getBarcodeHTML($product->codigo, 'EAN13', 3, 60) !!} </div>           
             
            <h1 style="font-weight: bold; font-size: 40px; margin:auto;">{{ $product->codigo }}</h1>
             
        @endforeach
    </div>

   </body>
</html>
