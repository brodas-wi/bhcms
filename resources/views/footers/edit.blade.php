<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Editar Footer</title>

        <!-- CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.21.7/css/grapes.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 0;
                height: 100vh;
                overflow: hidden;
            }

            .main-wrapper {
                display: flex;
                flex-direction: column;
                height: 100vh;
                width: 100vw;
            }

            .editor-toolbar {
                background: #444;
                height: 50px;
                min-height: 50px;
                display: flex;
                align-items: center;
                padding: 0 15px;
                gap: 15px;
                color: white;
            }

            .toolbar-left {
                display: flex;
                align-items: center;
                flex: 1;
                gap: 20px;
            }

            .editor-title {
                font-weight: 500;
                font-size: 16px;
            }

            .editor-actions {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .toolbar-center {
                display: flex;
                justify-content: center;
                flex: 1;
            }

            .device-buttons {
                display: flex;
                gap: 5px;
                background: rgba(255, 255, 255, 0.1);
                padding: 3px;
                border-radius: 4px;
            }

            .toolbar-right {
                display: flex;
                align-items: center;
                flex: 1;
                justify-content: flex-end;
                gap: 15px;
            }

            .toolbar-btn {
                background: none;
                border: none;
                color: white;
                padding: 6px 12px;
                cursor: pointer;
                font-size: 14px;
                display: flex;
                align-items: center;
                gap: 5px;
                border-radius: 4px;
                transition: background-color 0.2s;
            }

            .toolbar-btn:hover {
                background: rgba(255, 255, 255, 0.15);
            }

            .toolbar-btn.active {
                background: rgba(33, 150, 243, 0.3);
                color: #2196F3;
            }

            #footer-name {
                width: 200px;
                height: 32px;
                font-size: 14px;
                background: rgba(255, 255, 255, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.2);
                color: white;
                border-radius: 4px;
                padding: 0 10px;
            }

            #footer-name::placeholder {
                color: rgba(255, 255, 255, 0.5);
            }

            .editor-container {
                display: flex;
                flex: 1;
                overflow: hidden;
                position: relative;
                width: 100%;
                height: calc(100vh - 50px);
            }

            #gjs {
                flex: 1;
                overflow: hidden;
                position: relative;
                width: 100%;
                height: 100%;
            }

            .editor-sidebar {
                width: 260px;
                min-width: 260px;
                background: #f5f5f5;
                border-left: 1px solid #ddd;
                display: flex;
                flex-direction: column;
                height: 100%;
                overflow: hidden;
            }

            .sidebar-tabs {
                background: #444;
                padding: 5px 5px 0 5px;
                min-height: 41px;
            }

            .sidebar-tabs .nav-tabs {
                border: none;
            }

            .sidebar-tabs .nav-link {
                color: #fff;
                border: none;
                padding: 5px 10px;
                font-size: 13px;
            }

            .sidebar-tabs .nav-link.active {
                background: #f5f5f5;
                color: #444;
            }

            .sidebar-content {
                flex: 1;
                overflow-y: auto;
                height: calc(100% - 41px);
            }

            .tab-content {
                height: 100%;
            }

            .tab-pane {
                height: 100%;
                overflow-y: auto;
            }

            /* Editor styles */
            .gjs-editor {
                position: relative !important;
            }

            .gjs-cv-canvas {
                background-color: #f5f5f5 !important;
                transition: all 0.3s ease !important;
            }

            .gjs-frame-wrapper {
                display: flex !important;
                justify-content: center !important;
                align-items: flex-start !important;
                overflow: auto !important;
            }

            .code-editor {
                height: 400px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            .modal-xl {
                max-width: 90%;
            }
        </style>
    </head>

    <body>
        <div class="main-wrapper">
            <!-- Toolbar -->
            <div class="editor-toolbar">
                <div class="toolbar-left">
                    <div class="editor-title">Editor de Footer</div>
                    <div class="editor-actions">
                        <button class="toolbar-btn" id="visibility" title="Toggle Borders">
                            <i class="fas fa-border-none"></i>
                        </button>
                        <button class="toolbar-btn" id="undo" title="Undo">
                            <i class="fas fa-undo"></i>
                        </button>
                        <button class="toolbar-btn" id="redo" title="Redo">
                            <i class="fas fa-redo"></i>
                        </button>
                        <button class="toolbar-btn" id="clean-all" title="Clear Canvas">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="toolbar-center">
                    <div class="device-buttons">
                        <button class="toolbar-btn device-button active" data-device="desktop" title="Desktop View">
                            <i class="fas fa-desktop"></i>
                        </button>
                        <button class="toolbar-btn device-button" data-device="tablet" title="Tablet View">
                            <i class="fas fa-tablet-alt"></i>
                        </button>
                        <button class="toolbar-btn device-button" data-device="mobile" title="Mobile View">
                            <i class="fas fa-mobile-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="toolbar-right">
                    <input type="text" id="footer-name" placeholder="Nombre del Footer" value="{{ $footer->name }}">
                    <input type="hidden" id="footer-id" value="{{ $footer->id }}">
                    <button class="toolbar-btn" id="code-btn" title="View Code">
                        <i class="fas fa-code"></i>
                    </button>
                    <button class="toolbar-btn" id="update-btn" title="Update">
                        <i class="fas fa-save"></i>
                    </button>
                </div>
            </div>

            <!-- Editor Container -->
            <div class="editor-container">
                <div id="gjs">
                    {!! $footer->content !!}
                </div>

                <!-- Sidebar -->
                <div class="editor-sidebar">
                    <div class="sidebar-tabs">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#blocks-panel">Bloques</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#styles-panel">Estilos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#layers-panel">Capas</a>
                            </li>
                        </ul>
                    </div>
                    <div class="sidebar-content">
                        <div class="tab-content">
                            <div id="blocks-panel" class="tab-pane active">
                                <div id="blocks"></div>
                            </div>
                            <div id="styles-panel" class="tab-pane">
                                <div id="styles-container"></div>
                            </div>
                            <div id="layers-panel" class="tab-pane">
                                <div id="layers-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Code Modal -->
        <div class="modal fade" id="codeModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header py-2">
                        <h5 class="modal-title">Editor de Código</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">HTML</h6>
                                    <button id="format-html" class="btn btn-sm btn-secondary">Formatear</button>
                                </div>
                                <div id="html-editor" class="code-editor"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">CSS</h6>
                                    <button id="format-css" class="btn btn-sm btn-secondary">Formatear</button>
                                </div>
                                <div id="css-editor" class="code-editor"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer py-2">
                        <button type="button" class="btn btn-sm btn-secondary"
                            data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-sm btn-primary" id="apply-code">Aplicar</button>
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

        <!-- Editor initialization -->
        <script>
            window.footerData = @json($footer ?? null);
        </script>
        <script src="{{ asset('js/editor-footer.js') }}"></script>
    </body>

</html>
