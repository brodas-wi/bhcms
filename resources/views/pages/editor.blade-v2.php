<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ isset($page->name) ? 'Editar Página: ' . $page->name : 'Crear Nueva Página' }}</title>

        <!-- Core CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <link href="https://unpkg.com/grapesjs@0.20.3/dist/css/grapes.min.css" rel="stylesheet">

        <!-- Custom Editor CSS -->
        <link href="{{ asset('css/editor-page.css') }}" rel="stylesheet">

        <style>
            /* Critical inline styles for initial load */
            body,
            html {
                margin: 0;
                height: 100vh;
                overflow: hidden;
            }

            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.9);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            }
        </style>
    </head>

    <body>
        <!-- Loading Overlay -->
        <div class="loading-overlay">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>

        <!-- Main Editor Layout -->
        <div class="editor-container">
            <!-- Top Toolbar -->
            <div class="editor-toolbar">
                <div class="toolbar-section">
                    <button id="back-btn" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <span class="page-title ms-2">{{ $page->name ?? 'Nueva Página' }}</span>
                </div>

                <div class="toolbar-section">
                    <div class="btn-group" role="group">
                        <button id="undo-btn" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-undo"></i>
                        </button>
                        <button id="redo-btn" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-redo"></i>
                        </button>
                    </div>

                    <div class="btn-group ms-2" role="group">
                        <button id="preview-btn" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button id="code-btn" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-code"></i>
                        </button>
                    </div>

                    <div class="device-controls ms-2">
                        <button data-device="desktop" class="btn btn-outline-secondary btn-sm active">
                            <i class="fas fa-desktop"></i>
                        </button>
                        <button data-device="tablet" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-tablet-alt"></i>
                        </button>
                        <button data-device="mobile" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-mobile-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="toolbar-section">
                    <button id="save-draft-btn" class="btn btn-outline-secondary btn-sm">
                        Guardar Borrador
                    </button>
                    <button id="publish-btn" class="btn btn-primary btn-sm ms-2">
                        Publicar
                    </button>
                </div>
            </div>

            <!-- Main Editor Area -->
            <div class="editor-content">
                <!-- Left Sidebar - Components & Blocks -->
                <div class="editor-sidebar editor-sidebar-left">
                    <div class="sidebar-header">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#blocks-tab">
                                    <i class="fas fa-th-large"></i> Bloques
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#plugins-tab">
                                    <i class="fas fa-puzzle-piece"></i> Plugins
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content sidebar-content">
                        <div id="blocks-tab" class="tab-pane fade show active">
                            <div id="blocks-container"></div>
                        </div>
                        <div id="plugins-tab" class="tab-pane fade">
                            <div id="plugins-container"></div>
                        </div>
                    </div>
                </div>

                <!-- Main Canvas -->
                <div class="editor-main">
                    <div id="gjs"></div>
                </div>

                <!-- Right Sidebar - Styles & Settings -->
                <div class="editor-sidebar editor-sidebar-right">
                    <div class="sidebar-header">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#styles-tab">
                                    <i class="fas fa-paint-brush"></i> Estilos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#settings-tab">
                                    <i class="fas fa-cog"></i> Configuración
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content sidebar-content">
                        <div id="styles-tab" class="tab-pane fade show active">
                            <div id="styles-container"></div>
                        </div>
                        <div id="settings-tab" class="tab-pane fade">
                            <div id="settings-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modals -->
        @include('pages.partials.modals.code-editor')
        @include('pages.partials.modals.save-version')
        @include('pages.partials.modals.page-settings')
        @include('pages.partials.modals.confirm-exit')

        <!-- Core Scripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script src="https://unpkg.com/grapesjs@0.20.3/dist/grapes.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/ace-builds/1.23.0/ace.js"></script>
        <script src="{{ asset('js/editor-page/routes.js') }}"></script>
        <!-- Editor Configuration -->
        <script>
            window.editorConfig = {
                csrfToken: '{{ csrf_token() }}',
                page: @json($page ?? null),
                routes: PageEditor.initializeRoutes(@json($page ?? null)),
                plugins: @json($availablePlugins),
                defaultStyles: @json($defaultStyles)
            };
        </script>

        <!-- Custom Editor Scripts -->
        <script src="{{ asset('js/editor-page/index.js') }}"></script>
        <script src="{{ asset('js/editor-page/utils.js') }}"></script>
        <script src="{{ asset('js/editor-page/notifications.js') }}"></script>
        <script src="{{ asset('js/editor-page/plugins.js') }}"></script>
        <script src="{{ asset('js/editor-page/editor.js') }}"></script>
        <script src="{{ asset('js/editor-page/events.js') }}"></script>
        <script src="{{ asset('js/editor-page/main.js') }}"></script>
    </body>

</html>
