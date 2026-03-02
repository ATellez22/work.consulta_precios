<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de Etiqueta</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <!-- Barcode library (mismo que usa el backend) -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <style>
        /* ── base ── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top left, #1e293b, #0f172a);
            min-height: 100vh;
            color: #e2e8f0;
        }

        /* Estilo especial para cuando se entra desde la pantalla principal para imprimir directo */
        body.autoprint-mode {
            background: white !important;
            color: black !important;
        }

        body.autoprint-mode .top-bar,
        body.autoprint-mode .left-panel,
        body.autoprint-mode .preview-info,
        body.autoprint-mode .right-panel h2 {
            display: none !important;
        }

        body.autoprint-mode .main-content {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            height: 100vh !important;
            padding: 0 !important;
            gap: 0 !important;
        }

        /* ── layout ── */
        /* Contenedor principal de la página */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Barra superior con titulo y acciones */
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 32px;
            background: rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .top-bar h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: #93c5fd;
        }

        .top-bar .subtitle {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 2px;
        }

        .actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        /* Boton genérico */
        .btn {
            padding: 10px 22px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: transform 0.1s, opacity 0.2s;
        }

        .btn:active {
            transform: scale(0.96);
        }

        /* Boton de guardar */
        .btn-save {
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #e2e8f0;
        }

        .btn-save:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        /* Boton de imprimir */
        .btn-print {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: #fff;
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.35);
        }

        .btn-print:hover {
            opacity: 0.9;
        }

        /* Boton de volver */
        .btn-back {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            font-size: 0.8rem;
            padding: 8px 14px;
        }

        .btn-back:hover {
            color: #e2e8f0;
        }

        /* ── main split ── */
        /* Contenido principal dividido en paneles */
        .main-content {
            flex: 1;
            display: flex;
            gap: 40px;
            padding: 40px 48px;
            align-items: flex-start;
        }

        /* ── left panel: sortable list ── */
        /* Panel izquierdo con la lista ordenable */
        .left-panel {
            flex: 1;
        }

        .left-panel h2 {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            margin-bottom: 20px;
        }

        #sortable-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Item individual de la lista ordenable */
        .sortable-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 20px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 14px;
            cursor: grab;
            user-select: none;
            transition: background 0.15s, border-color 0.15s, transform 0.15s;
        }

        .sortable-item:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(99, 102, 241, 0.4);
        }

        .sortable-item.sortable-chosen {
            background: rgba(99, 102, 241, 0.12);
            border-color: rgba(99, 102, 241, 0.6);
            transform: scale(1.02);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .sortable-item.sortable-ghost {
            opacity: 0.3;
        }

        /* Manija para arrastrar los items */
        .drag-handle {
            display: flex;
            flex-direction: column;
            gap: 3px;
            opacity: 0.35;
        }

        .drag-handle span {
            display: block;
            width: 18px;
            height: 2px;
            background: currentColor;
            border-radius: 2px;
        }

        /* Icono de cada bloque en la lista */
        .item-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Color para el bloque de descripción */
        .icon-description {
            background: rgba(59, 130, 246, 0.15);
        }

        /* Color para el bloque de precio */
        .icon-price {
            background: rgba(16, 185, 129, 0.15);
        }

        /* Color para el bloque de código de barras */
        .icon-barcode {
            background: rgba(245, 158, 11, 0.15);
        }

        /* Color para el bloque de código interno */
        .icon-code {
            background: rgba(168, 85, 247, 0.15);
        }

        /* Contenedor de texto del item */
        .item-info {
            flex: 1;
        }

        /* Etiqueta del nombre del campo */
        .item-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #e2e8f0;
        }

        /* Valor actual del campo (ej: precio o descripción) */
        .item-value {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 220px;
        }

        /* Badge que indica el tipo de dato */
        .item-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.06);
            color: #94a3b8;
        }

        /* ── right panel: label preview ── */
        /* Panel derecho con la vista previa */
        .right-panel {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .right-panel h2 {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #64748b;
            align-self: flex-start;
        }

        /* Envoltorio de la etiqueta de vista previa */
        .preview-wrapper {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        /* Canvas que simula la etiqueta física (color blanco y texto negro) */
        .label-canvas {
            width: 400px;
            height: 185px;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            overflow: hidden;
            color: black;
            /* Forzar color negro */
        }

        /* ── Estilos del label en pantalla: valores 2x proporcionales ── */
        .label-canvas * {
            margin: 0;
            padding: 0;
        }

        .label-canvas .content {
            text-align: center;
        }

        /* Bloque de descripción del producto */
        .label-canvas .description {
            margin-top: 20px;
            margin-bottom: 10px;
            /* Reducido a la mitad (era 20px) */
            font-size: 24px;
            font-weight: bold;
        }

        /* Bloque de precio del producto */
        .label-canvas .price {
            font-size: 28px;
            font-weight: bold;
        }

        /* Bloque del código de barras centrado */
        .label-canvas .barcode {
            display: flex;
            justify-content: center;
            margin-top: 5px;
            margin-bottom: 0px;
        }

        /* Bloque del código numérico interno */
        .label-canvas .code {
            font-weight: bold;
            font-size: 24px;
        }

        /* Información adicional debajo de la vista previa */
        .preview-info {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.07);
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 0.7rem;
            color: #64748b;
            text-align: center;
            line-height: 1.6;
        }

        /* Notificación temporal de éxito */
        .toast {
            position: fixed;
            bottom: 32px;
            right: 32px;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #34d399;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            opacity: 0;
            transform: translateY(16px);
            transition: opacity 0.3s, transform 0.3s;
            pointer-events: none;
            z-index: 100;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* ── @media print ── */
        @media print {

            /* Tamano exacto de la etiqueta */
            @page {
                size: 55mm 25mm;
                margin: 0;
            }

            body {
                background: white !important;
            }

            .top-bar,
            .left-panel,
            .right-panel h2,
            .preview-wrapper>.preview-info,
            .toast {
                display: none !important;
            }

            .main-content {
                padding: 0 !important;
                display: block !important;
            }

            .right-panel {
                display: block !important;
            }

            .preview-wrapper {
                background: none !important;
                border: none !important;
                padding: 0 !important;
            }

            /* Reset canvas al tamano real de la etiqueta */
            .label-canvas {
                width: 55mm !important;
                height: 25mm !important;
                border: none !important;
                border-radius: 0 !important;
                overflow: hidden !important;
            }

            /* Reset al CSS exacto de withoutDate/index.blade.php */
            /* Ajustado para que la escala 2x del editor coincida con la real */
            .label-canvas .description {
                margin-top: 5px !important;
                /* Reducido (era 10px) */
                margin-bottom: 5px !important;
                /* Reducido (era 10px) */
                font-size: 12px !important;
            }

            /* Bloque de precio del producto */
            .label-canvas .price {
                font-size: 14px !important;
                margin-bottom: 2px !important;
            }

            /* Bloque del código de barras centrado */
            .label-canvas .barcode {
                display: flex !important;
                justify-content: center !important;
                margin-top: 2px !important;
                margin-bottom: 0px !important;
            }

            /* Bloque del código numérico inferior */
            .label-canvas .code {
                font-size: 12px !important;
            }

            /* Escalar el SVG del barcode para el tamaño de impresión */
            .label-canvas .barcode svg {
                width: 90px !important;
                height: 25px !important;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrapper">

        <!-- Top Bar -->
        <header class="top-bar">
            <div>
                <h1>✦ Editor de Etiqueta</h1>
                <div class="subtitle">Arrastrá los bloques para reordenarlos</div>
            </div>
            <div class="actions">
                <button class="btn btn-back" onclick="window.close()">← Volver</button>
                <button class="btn btn-save" id="btn-save">💾 Guardar Orden</button>
                <button class="btn btn-print" onclick="window.print()">🖨️ Imprimir</button>
            </div>
        </header>

        <!-- Main -->
        <main class="main-content">

            <!-- Left: sortable blocks -->
            <div class="left-panel">
                <h2>Bloques de la etiqueta</h2>
                <ul id="sortable-list">
                    <!-- Items injected by JS from saved order -->
                </ul>
            </div>

            <!-- Right: label preview -->
            <div class="right-panel">
                <h2>Vista previa</h2>
                <div class="preview-wrapper">
                    <div class="label-canvas" id="label-preview">
                        <!-- Blocks injected by JS -->
                    </div>
                    <div class="preview-info">
                        Tamaño real: 55 × 25 mm<br>
                        Vista ampliada para edición
                    </div>
                </div>
            </div>

        </main>

    </div>

    <!-- Toast -->
    <div class="toast" id="toast">✓ Orden guardado</div>

    <script>
        // ── Datos del producto ──────────────────────────────────────────────
        @foreach($products as $product)
            const PRODUCT = {
                descripcion: @json($product->descripcion),
                precio: {{ intval($product->precio) }},
                codigo: @json($product->codigo),
            };
        @endforeach

        // Formato de precio: separador de miles con punto (ej: 1.234.567)
        const formatPrice = n => n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');


        const STORAGE_KEY = 'label_order_without_date';

        // ── Definición de bloques disponibles ──────────────────────────────
        const BLOCK_DEFS = {
            description: {
                label: 'Descripción',
                iconClass: 'icon-description',
                icon: '📝',
                badge: 'TEXTO',
                getValue: p => p.descripcion,
            },
            price: {
                label: 'Precio',
                iconClass: 'icon-price',
                icon: '💲',
                badge: 'PRECIO',
                getValue: p => `Gs ${formatPrice(p.precio)}`,
            },
            barcode: {
                label: 'Código de Barras',
                iconClass: 'icon-barcode',
                icon: '▮▮',
                badge: 'EAN-13',
                getValue: p => p.codigo,
            },
            code: {
                label: 'Código',
                iconClass: 'icon-code',
                icon: '#',
                badge: 'CÓDIGO',
                getValue: p => p.codigo,
            },
        };

        // ── Orden por defecto ───────────────────────────────────────────────
        const DEFAULT_ORDER = ['description', 'price', 'barcode', 'code'];

        function loadOrder() {
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved) {
                    const arr = JSON.parse(saved);
                    // Validate all keys are present
                    if (arr.length === DEFAULT_ORDER.length && arr.every(k => BLOCK_DEFS[k])) return arr;
                }
            } catch (e) { }
            return [...DEFAULT_ORDER];
        }

        function saveOrder(order) {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(order));
        }

        // ── Render sortable list ────────────────────────────────────────────
        function renderList(order) {
            const ul = document.getElementById('sortable-list');
            ul.innerHTML = '';
            order.forEach((key, i) => {
                const def = BLOCK_DEFS[key];
                const li = document.createElement('li');
                li.className = 'sortable-item';
                li.dataset.key = key;
                li.innerHTML = `
                <div class="drag-handle"><span></span><span></span><span></span></div>
                <div class="item-icon ${def.iconClass}">${def.icon}</div>
                <div class="item-info">
                    <div class="item-label">${def.label}</div>
                    <div class="item-value">${def.getValue(PRODUCT)}</div>
                </div>
                <span class="item-badge">${def.badge}</span>
            `;
                ul.appendChild(li);
            });
        }

        // ── Render label preview ────────────────────────────────────────────
        // Genera la misma estructura HTML que withoutDate/index.blade.php
        const BLOCK_HTML = {
            description: `<div class="description">${PRODUCT.descripcion}</div>`,
            price: `<div class="price">Gs ${formatPrice(PRODUCT.precio)}</div>`,
            barcode: `<div class="barcode"><svg id="bc-preview"></svg></div>`,
            code: `<div class="code">${PRODUCT.codigo}</div>`,
        };

        function renderPreview(order) {
            const preview = document.getElementById('label-preview');
            const inner = order.map(key => BLOCK_HTML[key]).join('');
            preview.innerHTML = `<div class="content">${inner}</div>`;

            // Renderizar codigo de barras con 2x de tamano para la vista en pantalla
            const bcEl = document.getElementById('bc-preview');
            if (bcEl) {
                try {
                    const paddedCode = String(PRODUCT.codigo).padStart(12, '0');
                    JsBarcode(bcEl, paddedCode, {
                        format: 'EAN13',
                        width: 2,
                        height: 30,
                        displayValue: false,
                        margin: 0,
                    });
                } catch (e) {
                    bcEl.parentNode.innerHTML =
                        `<span style="font-size:12px;color:#888;">[${PRODUCT.codigo}]</span>`;
                }
            }
        }

        // ── Init ────────────────────────────────────────────────────────────
        let currentOrder = loadOrder();
        renderList(currentOrder);
        renderPreview(currentOrder);

        // ── Init SortableJS ─────────────────────────────────────────────────
        Sortable.create(document.getElementById('sortable-list'), {
            animation: 180,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            handle: '.drag-handle',
            onEnd: function () {
                const items = document.querySelectorAll('#sortable-list .sortable-item');
                currentOrder = Array.from(items).map(el => el.dataset.key);
                renderPreview(currentOrder);
            }
        });

        // ── Save button ─────────────────────────────────────────────────────
        document.getElementById('btn-save').addEventListener('click', function () {
            saveOrder(currentOrder);
            const toast = document.getElementById('toast');
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 2200);
        });

        // ── Auto-impresion si viene desde la pantalla principal ──────────────
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('autoprint') === 'true') {
            document.body.classList.add('autoprint-mode');
            // Pequeño retardo para asegurar que JsBarcode y el DOM estén listos
            setTimeout(() => {
                window.print();
            }, 600);
        }
    </script>
</body>

</html>