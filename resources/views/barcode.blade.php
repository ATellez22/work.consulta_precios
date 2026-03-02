<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- TailwindCSS -->
    @vite('resources/css/app.css')

    <title>Barcode</title>
</head>

<body>

    <div class="md:flex justify-center items-center align-center mb-5">

        {!! DNS1D::getBarcodeHTML(str_pad((string) time(), 12, '0', STR_PAD_LEFT), 'EAN13') !!}

    </div>

    <div class="md:flex justify-center items-center align-center">

        {!! DNS1D::getBarcodeHTML(str_pad('7840235002683', 12, '0', STR_PAD_LEFT), 'EAN13') !!}

    </div>


</body>

</html>