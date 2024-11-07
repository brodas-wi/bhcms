<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($page->name) ? 'Editar Página: ' . $page->name : 'Crear Nueva Página' }}</title>

        {{-- Estilos CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/grapesjs@0.20.3/dist/css/grapes.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.css">

        {{-- Estilos del editor --}}
        @vite(['resources/css/editor/main.css', 'resources/css/editor/modals.css', 'resources/css/editor/panels.css'])

        {{-- Configuración del Editor --}}
        <script>
            window.editorConfig = {
                availablePlugins: @json($availablePlugins ?? []),
                serializedContent: @json($serializedContent ?? null),
                activePlugins: @json($activePlugins ?? []),
                defaultStyles: @json($defaultStyles ?? ''),
                pageData: {
                    id: {{ $page->id ?? 'null' }},
                    name: '{{ $page->name ?? '' }}',
                    version: '{{ $page->version ?? '1.0.0' }}',
                    templateId: {{ $page->template_id ?? 1 }},
                    userId: {{ auth()->id() ?? 'null' }}
                },
                routes: {
                    save: '{{ isset($page->id) ? route('pages.update', $page->id) : route('pages.store') }}',
                    index: '{{ route('pages.index') }}',
                    uploadImage: '{{ route('upload-image') }}',
                    getImages: '{{ route('get-images') }}'
                },
                csrf: '{{ csrf_token() }}'
            };
        </script>
    </head>

    <body>
        {{-- Main Content --}}
        @yield('content')

        {{-- Scripts base --}}
        <script src="https://unpkg.com/grapesjs@0.20.3"></script>
        <script src="https://unpkg.com/grapesjs-blocks-basic@1.0.1"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        {{-- CodeMirror y sus dependencias --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>

        {{-- Beautifier --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify-html.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify-css.min.js"></script>

        {{-- CodeMirror Addons --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldcode.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/brace-fold.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/xml-fold.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/comment-fold.min.js"></script>

        {{-- Editor Scripts --}}
        @vite(['resources/js/editor/index.js'])

        {{-- Scripts adicionales --}}
        @stack('scripts')
    </body>

</html>
