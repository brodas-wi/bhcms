@extends('layouts.editor')

@section('content')
    <div class="editor-container">
        {{-- Toolbar Superior --}}
        @include('pages.partials._toolbar')

        <div class="editor-row">
            {{-- Panel Izquierdo (Bloques) --}}
            <div class="panel__left">
                <div id="blocks"></div>
            </div>

            {{-- Canvas Principal del Editor --}}
            <div class="editor-canvas">
                <div id="gjs"></div>
            </div>

            {{-- Panel Derecho (Estilos, Capas, Atributos) --}}
            <div class="panel__right">
                <div class="panel__right-wrapper">
                    <div id="layers-container"></div>
                    <div id="styles-container"></div>
                    <div id="traits-container"></div>
                </div>
            </div>
        </div>

        {{-- Área de Editor de Código --}}
        <div class="code-editor" style="display: none;">
            <div class="code-editor-content">
                <div class="editor-section">
                    <h3>HTML</h3>
                    <textarea id="html-editor"></textarea>
                </div>
                <div class="editor-section">
                    <h3>CSS</h3>
                    <textarea id="css-editor"></textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Modales --}}
    @include('pages.partials._modals')

    {{-- Notificaciones --}}
    <div id="editor-notifications"></div>

    {{-- Scripts específicos de la página --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Inicializar el editor cuando el DOM esté listo
                const editor = window.editor = grapesjs.init(window.editorConfig);

                // Inicializar utilidades
                const notifications = new EditorNotifications();
                const keyboardShortcuts = new KeyboardShortcuts(editor);
                const componentManager = new ComponentManager(editor);

                // Setup de eventos
                setupEventListeners(editor);

                // Cargar contenido inicial si existe
                if (window.editorConfig.serializedContent) {
                    editor.setComponents(window.editorConfig.serializedContent);
                    notifications.success('Contenido cargado correctamente');
                }
            });
        </script>
    @endpush
@endsection
