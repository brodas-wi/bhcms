<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $page->description }}">
        <title>{{ $page->name }}</title>

        <!-- Estilos externos -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

        <!-- Estilos de la plantilla -->
        @if ($page->template && $page->template->styles)
            <style type="text/css">
                /* Template Styles */
                {!! $page->template->styles !!}
            </style>
        @endif

        <!-- Estilos del Navbar -->
        @if ($page->navbar && $page->navbar->css)
            <style type="text/css">
                /* Navbar Styles */
                {!! $page->navbar->css !!}
            </style>
        @endif

        <!-- Estilos del Footer -->
        @if ($page->footer && $page->footer->css)
            <style type="text/css">
                /* Footer Styles */
                {!! $page->footer->css !!}
            </style>
        @endif

        <!-- Estilos de plugins -->
        @if (!empty($pluginStyles))
            <style type="text/css">
                /* Plugin Styles */
                {!! $pluginStyles !!}
            </style>
        @endif

        <!-- Hook para head -->
        @php $hookSystem->doAction('head') @endphp
    </head>

    <body>
        <!-- Navbar -->
        @if ($page->navbar && $page->navbar->is_active)
            {!! $page->navbar->content !!}
        @endif

        <!-- Contenido principal -->
        <main class="page-content">
            {!! $content !!}
        </main>

        <!-- Footer -->
        @if ($page->footer)
            {!! $page->footer->content !!}
        @endif

        <!-- Scripts externos -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

        <!-- Scripts de plugins -->
        @if (!empty($pluginScripts))
            <script type="text/javascript">
                (function() {
                    'use strict';
                    /* Plugin Scripts */
                    {!! $pluginScripts !!}
                })();
            </script>
        @endif

        <!-- Hook para footer -->
        @php $hookSystem->doAction('footer') @endphp
    </body>

</html>
