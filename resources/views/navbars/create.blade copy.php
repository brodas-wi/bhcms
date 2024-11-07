<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Navbar Editor</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.7/css/grapes.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <style>
            /* Variables globales */
            :root {
                --header-height: 60px;
                --sidebar-width: 300px;
                --primary-color: #0d6efd;
                --border-color: #dee2e6;
                --background-color: #f8f9fa;
                --text-color: #212529;
            }

            /* Reset y estilos base */
            body {
                margin: 0;
                padding: 0;
                background-color: var(--background-color);
                color: var(--text-color);
                overflow: hidden;
                font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            }

            /* Layout principal */
            .app-container {
                height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* Header */
            .header {
                height: var(--header-height);
                background: white;
                border-bottom: 1px solid var(--border-color);
                padding: 0.5rem 1rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: sticky;
                top: 0;
                z-index: 1000;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            /* Contenedor principal del editor */
            .editor-container {
                display: flex;
                height: calc(100vh - var(--header-height));
                overflow: hidden;
            }

            /* Contenedor del canvas */
            .canvas-container {
                flex: 1;
                height: 100%;
                background: white;
                position: relative;
                overflow: hidden;
            }

            /* Sidebar */
            .sidebar {
                width: var(--sidebar-width);
                background: white;
                border-left: 1px solid var(--border-color);
                height: 100%;
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }

            /* Estilos para el campo de nombre del navbar */
            .navbar-name {
                max-width: 300px;
            }

            .navbar-name input {
                border-radius: 4px;
                border: 1px solid var(--border-color);
                padding: 0.5rem;
                width: 100%;
            }

            /* Botones de dispositivo */
            .device-button {
                padding: 0.5rem;
                border: 1px solid var(--border-color);
                background: white;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .device-button.active {
                background: var(--primary-color);
                color: white;
                border-color: var(--primary-color);
            }

            /* Personalización de GrapesJS */
            #gjs {
                border: none;
                height: 100%;
            }

            /* Ajustes de los paneles de GrapesJS */
            .gjs-pn-panels {
                position: relative;
            }

            .gjs-pn-views {
                display: none;
            }

            .gjs-pn-devices-c {
                display: none;
            }

            .gjs-pn-commands {
                position: sticky !important;
                top: 0;
                background: white;
                z-index: 1;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            /* Botones de la barra de herramientas */
            .gjs-pn-btn {
                font-size: 16px !important;
                padding: 6px !important;
                min-width: 26px !important;
                margin-right: 2px !important;
            }

            .gjs-pn-btn.gjs-pn-active {
                background-color: var(--primary-color) !important;
                color: white !important;
            }

            /* Estilos del canvas */
            .gjs-frame-wrapper {
                height: calc(100% - 45px) !important;
            }

            .gjs-cv-canvas {
                background-color: var(--background-color);
                width: 100% !important;
                height: 100% !important;
                top: 0 !important;
            }

            /* Elementos seleccionados y hover */
            .gjs-selected {
                outline: 2px solid var(--primary-color) !important;
                outline-offset: -2px !important;
            }

            .gjs-hovered {
                outline: 1px solid var(--primary-color) !important;
                outline-offset: -1px !important;
            }

            /* Panel de bloques */
            #blocks {
                padding: 1rem;
            }

            .gjs-block {
                width: 100%;
                min-height: 50px;
                margin-bottom: 10px;
                transition: all 0.2s ease;
                cursor: grab;
            }

            .gjs-block:hover {
                background-color: var(--background-color);
            }

            /* Panel de estilos */
            #styles-container {
                padding: 1rem;
            }

            /* Panel de capas */
            #layers-container {
                padding: 1rem;
                overflow-y: auto;
            }

            /* Modal de código */
            .code-modal .modal-dialog {
                max-width: 800px;
            }

            .code-editor {
                height: 400px;
                border: 1px solid var(--border-color);
                border-radius: 4px;
            }

            /* Scrollbar personalizada */
            .sidebar::-webkit-scrollbar {
                width: 6px;
            }

            .sidebar::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .sidebar::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 3px;
            }

            .sidebar::-webkit-scrollbar-thumb:hover {
                background: #555;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .sidebar {
                    position: fixed;
                    right: 0;
                    transform: translateX(100%);
                    transition: transform 0.3s ease;
                    z-index: 1000;
                }

                .sidebar.active {
                    transform: translateX(0);
                }

                .navbar-name {
                    max-width: 150px;
                }
            }

            /* Toasts */
            .toast-container {
                z-index: 1060;
            }

            /* Acordeón del sidebar */
            .accordion-button:not(.collapsed) {
                background-color: var(--background-color);
                color: var(--primary-color);
            }

            .accordion-button:focus {
                box-shadow: none;
                border-color: var(--border-color);
            }
        </style>
    </head>

    <body>
        <div class="app-container">
            <header class="header">
                <div class="d-flex align-items-center gap-3">
                    <div class="navbar-name">
                        <input type="text" id="name" class="form-control" placeholder="Nombre del Navbar"
                            required>
                    </div>
                    <div class="btn-group d-none d-md-flex">
                        <button class="device-button active" data-device="desktop" title="Desktop View">
                            <i class="fas fa-desktop"></i>
                        </button>
                        <button class="device-button" data-device="tablet" title="Tablet View">
                            <i class="fas fa-tablet-alt"></i>
                        </button>
                        <button class="device-button" data-device="mobile" title="Mobile View">
                            <i class="fas fa-mobile-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button id="code-btn" class="btn btn-outline-secondary">
                        <i class="fas fa-code"></i>
                        <span class="d-none d-md-inline ms-1">Editor</span>
                    </button>
                    <button id="save-btn" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        <span class="d-none d-md-inline ms-1">Guardar</span>
                    </button>
                    <button class="btn btn-outline-primary d-md-none" id="toggle-sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </header>

            <div class="editor-container">
                <div class="canvas-container">
                    <div id="gjs"></div>
                </div>
                <div class="sidebar">
                    <div class="accordion" id="sidebarAccordion">
                        <!-- Blocks Panel -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#blocksPanel">
                                    <i class="fas fa-cube me-2"></i> Bloques
                                </button>
                            </h2>
                            <div id="blocksPanel" class="accordion-collapse show collapse"
                                data-bs-parent="#sidebarAccordion">
                                <div class="accordion-body">
                                    <div id="blocks"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Styles Panel -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#stylesPanel">
                                    <i class="fas fa-paint-brush me-2"></i> Estilos
                                </button>
                            </h2>
                            <div id="stylesPanel" class="accordion-collapse collapse"
                                data-bs-parent="#sidebarAccordion">
                                <div class="accordion-body">
                                    <div id="styles-container"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Layers Panel -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#layersPanel">
                                    <i class="fas fa-layer-group me-2"></i> Capas
                                </button>
                            </h2>
                            <div id="layersPanel" class="accordion-collapse collapse"
                                data-bs-parent="#sidebarAccordion">
                                <div class="accordion-body">
                                    <div id="layers-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Code Editor Modal -->
            <div class="modal fade" id="codeModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-code me-2"></i>Editor de Código
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="nav nav-tabs mb-3">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#html-tab">
                                        <i class="fab fa-html5 me-1"></i>HTML
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#css-tab">
                                        <i class="fab fa-css3-alt me-1"></i>CSS
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="html-tab">
                                    <div class="d-flex justify-content-end mb-2">
                                        <button class="btn btn-sm btn-secondary" id="format-html">
                                            <i class="fas fa-magic me-1"></i>Formatear
                                        </button>
                                    </div>
                                    <div id="html-editor" class="code-editor"></div>
                                </div>
                                <div class="tab-pane fade" id="css-tab">
                                    <div class="d-flex justify-content-end mb-2">
                                        <button class="btn btn-sm btn-secondary" id="format-css">
                                            <i class="fas fa-magic me-1"></i>Formatear
                                        </button>
                                    </div>
                                    <div id="css-editor" class="code-editor"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" id="apply-code">
                                <i class="fas fa-check me-1"></i>Aplicar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.7/grapes.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/ace.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/mode-html.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/mode-css.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.23.4/theme-monokai.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-html.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.7/beautify-css.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="{{ asset('js/editor-navbar.js') }}"></script>
    </body>

</html>
