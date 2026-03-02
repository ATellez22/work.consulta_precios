<!DOCTYPE html>
<html lang="es" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <!-- TailwindCSS -->
    @vite('resources/css/app.css')

    <!-- Sweet Alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <title>Consulta de Artículos</title>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .mesh-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background:
                radial-gradient(at 0% 0%, hsla(210, 100%, 20%, 0.5) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(190, 100%, 20%, 0.5) 0, transparent 50%),
                radial-gradient(at 100% 100%, hsla(220, 100%, 20%, 0.5) 0, transparent 50%),
                radial-gradient(at 0% 100%, hsla(200, 100%, 20%, 0.5) 0, transparent 50%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .outfit {
            font-family: 'Outfit', sans-serif;
        }

        .glow-input:focus {
            box-shadow: 0 0 25px rgba(59, 130, 246, 0.2);
            border-color: rgba(59, 130, 246, 0.5);
        }

        ::-webkit-file-upload-button {
            display: none;
        }
    </style>
</head>

<body class="text-slate-200">
    <div class="mesh-gradient"></div>

    <form id="myform" action="{{ route('query.print') }}" method="POST" enctype="multipart/form-data"
        class="min-h-screen flex flex-col">
        @csrf

        <!-- Navigation / Header -->
        <header
            class="w-full px-6 py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-white/10 glass-card sticky top-0 z-10 overflow-hidden">

            <!-- Actions -->
            <div class="flex flex-wrap flex-1 md:ml-auto gap-6">

                <!-- Upload -->
                <div class="flex items-center bg-slate-800/70 border border-white/10 rounded-xl px-4 py-2 space-x-3">

                    <label for="file_upload"
                        class="cursor-pointer text-xs font-bold uppercase tracking-wider text-slate-300 hover:text-white transition-colors">
                        Subir Archivo
                    </label>

                    <input type="file" id="file_upload" name="file_upload" accept=".csv,.xlsx,.xls" class="hidden"
                        onchange="updateFileName(this)" />

                    <span id="file_name" class="text-xs text-blue-400 font-medium truncate max-w-[140px]">
                        Ninguno
                    </span>

                    <button type="submit" name="btn_upload" value="upload"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded-lg text-sm font-semibold shadow-lg shadow-blue-500/20 transition active:scale-95">

                        Subir

                    </button>

                </div>

                <!-- Buttons -->
                <div class="flex items-center gap-5">

                    <button type="button" id="btn_open_editor"
                        class="px-8 py-4 bg-blue-600 hover:bg-blue-500 rounded-lg text-sm font-semibold shadow-lg shadow-blue-500/20 transition active:scale-95">

                        Imprimir

                    </button>

                </div>

            </div>

        </header>

        <!-- Main Content -->
        <main class="flex-grow flex items-center justify-center p-6">
            <div class="glass-card w-full max-w-4xl rounded-3xl p-8 md:p-12 transition-all duration-500">
                <div class="space-y-12">
                    <!-- Input Section -->
                    <div class="relative">
                        <input type="hidden" id="txt_cod" name="txt_cod" />
                        <input type="text" id="txt_codigo" name="txt_codigo"
                            class="w-full bg-slate-900/50 border-2 border-white/10 rounded-2xl py-8 px-6 text-5xl md:text-7xl outfit font-extrabold text-blue-400 text-center placeholder-slate-600 outline-none transition-all glow-input"
                            placeholder="Escanee o ingrese código" autofocus onblur="buscar()" />
                        <div
                            class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 bg-slate-900 text-slate-500 text-xs font-bold uppercase tracking-widest rounded-full border border-white/10">
                            Código de Barras
                        </div>
                    </div>

                    <!-- Result Section -->
                    <div class="text-center space-y-4">
                        <div class="min-h-[140px] flex items-center justify-center">
                            <label id="lb_descripcion"
                                class="text-4xl md:text-6xl font-semibold text-slate-100 leading-tight">
                                {{-- Descripcion here --}}
                            </label>
                        </div>
                        <div class="min-h-[100px] flex items-center justify-center">
                            <label id="lb_precio"
                                class="text-6xl md:text-8xl outfit font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">
                                {{-- Precio here --}}
                            </label>
                        </div>
                    </div>

                    <!-- Session Status -->
                    @if(session('status'))
                        <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl text-center">
                            <span class="text-blue-400 font-medium">{{ session('status') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer
            class="w-full px-8 py-6 flex justify-between items-center text-slate-500 text-xs border-t border-white/10">
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span>Sistema en Línea</span>
            </div>
            <div class="font-medium tracking-widest uppercase">V2.0 Premium</div>
        </footer>
    </form>

    <script type="text/javascript">
        function updateFileName(input) {
            const fileName = input.files[0] ? input.files[0].name : 'Ningún archivo';
            document.getElementById('file_name').textContent = fileName;
        }
    </script>
    <script type="text/javascript" src="{{ asset('js/query.js') }}"></script>
    <script type="text/javascript">
        document.getElementById('btn_open_editor').addEventListener('click', function () {
            const codigo = document.getElementById('txt_cod').value;
            if (!codigo) {
                swal('Atención', 'Primero escaneá o ingresá un código de producto.', 'warning');
                return;
            }
            const url = '/editor/' + encodeURIComponent(codigo);
            window.open(url, '_blank');
        });
    </script>
</body>


</html>