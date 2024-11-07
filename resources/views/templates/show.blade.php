@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Editar Plantilla</h1>

        <form id="template-form" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <img src="{{ $template->thumbnail }}" class="card-img-top" id="thumbnail-preview"
                            alt="{{ $template->name }}">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="thumbnail" class="form-label">Imagen de portada</label>
                                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                            </div>
                            <div class="d-none mb-3">
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                    value="{{ $template->user->id }}" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de la Plantilla</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $template->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required>{{ $template->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Personalización
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="font_family" class="form-label">Fuente Principal</label>
                                <select class="form-select" id="font_family" name="font_family">
                                    <option value="Arial" {{ $template->font_family == 'Arial' ? 'selected' : '' }}>Arial
                                    </option>
                                    <option value="Helvetica" {{ $template->font_family == 'Helvetica' ? 'selected' : '' }}>
                                        Helvetica</option>
                                    <option value="Times New Roman"
                                        {{ $template->font_family == 'Times New Roman' ? 'selected' : '' }}>Times New Roman
                                    </option>
                                    <option value="Georgia" {{ $template->font_family == 'Georgia' ? 'selected' : '' }}>
                                        Georgia</option>
                                    <option value="Verdana" {{ $template->font_family == 'Verdana' ? 'selected' : '' }}>
                                        Verdana</option>
                                    <option value="Roboto" {{ $template->font_family == 'Roboto' ? 'selected' : '' }}>
                                        Roboto
                                    </option>
                                    <option value="Open Sans"
                                        {{ $template->font_family == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                                    <option value="Lato" {{ $template->font_family == 'Lato' ? 'selected' : '' }}>Lato
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="custom_css" class="form-label">CSS</label>
                                <div class="d-flex justify-content-end mb-2">
                                    <button type="button" id="format-css" class="btn btn-secondary btn-sm">Formatear
                                        CSS</button>
                                </div>
                                <textarea class="form-control" id="custom_css" name="custom_css" rows="5">{{ $template->styles }}</textarea>
                            </div>
                            <button id="save" type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            Información de la Plantilla
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="landing" {{ $template->type == 'landing' ? 'selected' : '' }}>Landing
                                    </option>
                                    <option value="blog" {{ $template->type == 'blog' ? 'selected' : '' }}>Blog</option>
                                    <option value="ecommerce" {{ $template->type == 'ecommerce' ? 'selected' : '' }}>
                                        E-commerce</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="layout" class="form-label">Diseño</label>
                                <select class="form-select" id="layout" name="layout">
                                    <option value="one_column" {{ $template->layout == 'one_column' ? 'selected' : '' }}>
                                        Una Columna</option>
                                    <option value="two_columns" {{ $template->layout == 'two_columns' ? 'selected' : '' }}>
                                        Dos Columnas</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Categoría</label>
                                <select class="form-select" id="category" name="category">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $template->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p><strong>Autor:</strong> {{ $template->user->name }}</p>
                            <p><strong>Versión:</strong> {{ $template->version }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/theme/monokai.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify-css.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var cssContent = document.getElementById("custom_css").value;
            var formattedCSS = css_beautify(cssContent, {
                indent_size: 2,
                indent_char: ' ',
                max_preserve_newlines: 1,
                preserve_newlines: true,
                keep_array_indentation: false,
                break_chained_methods: false,
                indent_scripts: 'normal',
                brace_style: 'collapse',
                space_before_conditional: true,
                unescape_strings: false,
                jslint_happy: false,
                end_with_newline: false,
                wrap_line_length: 0,
                indent_inner_html: false,
                comma_first: false,
                e4x: false,
                indent_empty_lines: false
            });

            var editor = CodeMirror.fromTextArea(document.getElementById("custom_css"), {
                lineNumbers: true,
                mode: "text/css",
                theme: "monokai",
                lineWrapping: true,
                viewportMargin: Infinity
            });

            // Función para formatear el CSS
            function formatCSS() {
                var cssContent = editor.getValue();
                var formattedCSS = css_beautify(cssContent, {
                    indent_size: 2,
                    indent_char: ' ',
                    max_preserve_newlines: 1,
                    preserve_newlines: true,
                    keep_array_indentation: false,
                    break_chained_methods: false,
                    indent_scripts: 'normal',
                    brace_style: 'collapse',
                    space_before_conditional: true,
                    unescape_strings: false,
                    jslint_happy: false,
                    end_with_newline: false,
                    wrap_line_length: 0,
                    indent_inner_html: false,
                    comma_first: false,
                    e4x: false,
                    indent_empty_lines: false
                });
                editor.setValue(formattedCSS);
            }

            editor.setValue(formattedCSS);

            function updateFontFamily() {
                var fontFamily = document.getElementById('font_family').value;
                var cssContent = editor.getValue();
                var bodyRegex = /body\s*{[^}]*}/;
                var fontFamilyRegex = /font-family:\s*[^;]+;/;

                if (bodyRegex.test(cssContent)) {
                    if (fontFamilyRegex.test(cssContent)) {
                        cssContent = cssContent.replace(fontFamilyRegex, `font-family: ${fontFamily};`);
                    } else {
                        cssContent = cssContent.replace(bodyRegex, function(match) {
                            return match.slice(0, -1) + `font-family: ${fontFamily};\n}`;
                        });
                    }
                } else {
                    cssContent += `\nbody {\n  font-family: ${fontFamily};\n}`;
                }

                editor.setValue(cssContent);
            }

            // Event listener para el botón de formateo
            document.getElementById('format-css').addEventListener('click', function(e) {
                e.preventDefault();
                formatCSS();
            });

            document.getElementById('font_family').addEventListener('change', updateFontFamily);

            // Previsualización de la imagen
            const thumbnailInput = document.getElementById('thumbnail');
            const thumbnailPreview = document.getElementById('thumbnail-preview');

            thumbnailInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        thumbnailPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            document.getElementById('template-form').addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                formData.set('styles', editor.getValue());

                fetch('{{ route('templates.update', $template->id) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Plantilla guardada exitosamente');
                            window.location.href = '{{ route('templates.index') }}';
                        } else {
                            throw new Error('Error al guardar la plantilla');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error al guardar la plantilla');
                    });
            });

            function extractFontFamily() {
                var styles = document.getElementById('custom_css').value;
                var fontFamilyMatch = styles.match(/body\s*{[^}]*font-family:\s*([^;}]+)[;}]/);
                if (fontFamilyMatch && fontFamilyMatch[1]) {
                    var fontFamily = fontFamilyMatch[1].trim().replace(/['"]/g, '');
                    var fontSelect = document.getElementById('font_family');
                    for (var i = 0; i < fontSelect.options.length; i++) {
                        if (fontSelect.options[i].value.toLowerCase() === fontFamily.toLowerCase()) {
                            fontSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            }

            // Ejecutar la función al cargar la página
            extractFontFamily();

            editor.on("change", function() {
                document.getElementById("custom_css").value = editor.getValue();
                extractFontFamily();
            });
        });
    </script>
@endsection

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/theme/monokai.min.css">
@endsection
