<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>

    <!-- TailwindCSS -->
    @vite('resources/css/app.css')

    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <title>Consultas</title>
</head>

<body class="p-4 mb-2 bg-gray-400 text-black">
    <form id="myform" action="{{ route('query.print') }}" method="POST">
        <nav class="flex flex-col items-center bg-teal-600 p-6">
            <div class="flex items-center text-white mb-6">
                <svg class="fill-current h-8 w-8 mr-2" width="54" height="54" viewBox="0 0 54 54" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.5 22.1c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05zM0 38.3c1.8-7.2 6.3-10.8 13.5-10.8 10.8 0 12.15 8.1 17.55 9.45 3.6.9 6.75-.45 9.45-4.05-1.8 7.2-6.3 10.8-13.5 10.8-10.8 0-12.15-8.1-17.55-9.45-3.6-.9-6.75.45-9.45 4.05z" />
                </svg>
                <span class="font-semibold text-xl tracking-tight">Consulta de Articulos</span>
            </div>

            @csrf
            <div class="flex items-center">
                <button type="submit" class="bg-indigo-700 hover:bg-indigo-600 rounded p-3 border border-gray-200 font-bold mr-2" id="btn_update" name="btn_update" value="update">
                    Actualizar productos
                </button>
                <button type="submit" class="bg-blue-700 hover:bg-blue-600 rounded p-3 border border-gray-200 font-bold mr-2" formtarget="_blank" id="btn_print" name="btn_print" value="print">
                    Imprimir sin fecha
                </button>
                <button type="submit" class="bg-orange-700 hover:bg-orange-600 rounded p-3 border border-gray-200 font-bold" formtarget="_blank" id="btn_print_with_fecha" name="btn_print_with_fecha" value="print_with_fecha">
                    Imprimir con fecha
                </button>
            </div>
        </nav>

        <div class="p-3 mb-2 bg-white h-100">
            <div class="md:container mx-auto">
                <div class="md:flex justify-center items-center">
                    <input type="hidden" id="txt_cod" name="txt_cod" />

                    <input type="text" id="txt_codigo" name="txt_codigo" class="block p-4 w-3/4 text-7xl text-gray-900 bg-gray-50 rounded-lg
                border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600
                dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500
                dark:focus:border-blue-500 text-center" style="font-weight: bold" placeholder="Ingrese un código" onblur="buscar()" />
                </div>
            </div>

            <div class="md:container mx-auto mt-8">
                <div class="md:flex justify-center items-center">
                    <label id="lb_descripcion" name="lb_descripcion" class="text-8xl" style="font-weight:500"></label>
                </div>
            </div>

            <div class="md:container mx-auto mt-4">
                <div class="md:flex justify-center items-center">
                    <label id="lb_precio" name="lb_precio" class="text-8xl text-teal-600" style="font-weight: bold"></label>
                </div>
            </div>

            <div class="md:flex justify-center align-center text-bold">
                <h1 class="text-bold text-3xl">{{ session('status') }}</h1>
            </div>
        </div>
    </form>


    <footer class="blockquote-footer-white">V2.0</footer>

    <script type="text/javascript" src="{{ asset('js/query.js') }}"></script>

</body>

</html>