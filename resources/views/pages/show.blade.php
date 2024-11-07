@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/theme/monokai.min.css">
    <style>
        @media (max-width: 991px) {
            .order-lg-2 {
                order: -1;
            }
        }

        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .custom_html {
            height: auto;
            min-height: 300px;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1 class="mb-4">Datos de Página</h1>

        <form id="page-form" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="update_source" value="show_view">
            <input type="hidden" name="selected_version" id="selected-version" value="{{ $page->version }}">
            <div class="row">
                <div class="col-lg-4 order-lg-2 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Información de la página
                        </div>
                        <div class="card-body">
                            <!-- Contenido de la información de la página -->
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-select" id="type" name="type">
                                    <option value="landing" {{ $page->type == 'landing' ? 'selected' : '' }}>Landing
                                    </option>
                                    <option value="blog" {{ $page->type == 'blog' ? 'selected' : '' }}>Blog</option>
                                    <option value="ecommerce" {{ $page->type == 'ecommerce' ? 'selected' : '' }}>E-commerce
                                    </option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="layout" class="form-label">Diseño</label>
                                <select class="form-select" id="layout" name="layout">
                                    <option value="one_column" {{ $page->layout == 'one_column' ? 'selected' : '' }}>Una
                                        Columna</option>
                                    <option value="two_columns" {{ $page->layout == 'two_columns' ? 'selected' : '' }}>Dos
                                        Columnas</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Categoría</label>
                                <select class="form-select" id="category" name="category_id">
                                    <option value="">Sin categoría</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $page->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p><strong>Slug:</strong> <span id="page-slug">{{ $page->slug }}</span></p>
                            <div class="mb-3">
                                <label for="version-select" class="form-label">Versión:</label>
                                <select class="form-select" id="version-select" name="version">
                                    <option value="{{ $page->version }}">{{ $page->version }} (Actual)</option>
                                    @foreach ($page->versions()->where('version', '!=', $page->version)->orderBy('version', 'desc')->get() as $version)
                                        <option value="{{ $version->version }}">{{ $version->version }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="set-as-current">Establecer
                                        como versión actual</button>
                                    <button type="button" class="btn btn-danger btn-sm" id="delete-version">Eliminar
                                        versión</button>
                                </div>
                            </div>
                            <p><strong>Autor:</strong> <span id="author-name">{{ $page->user->name }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 order-lg-1">
                    <div class="card mb-4">
                        <img src="{{ $page->thumbnail }}" class="card-img-top" id="thumbnail-preview"
                            alt="{{ $page->name }}">
                        <div class="card-body">
                            <!-- Contenido principal de la página -->
                            <div class="mb-3">
                                <label for="thumbnail" class="form-label">Imagen de portada</label>
                                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/*">
                            </div>
                            <div class="d-none">
                                <input type="text" class="form-control" id="user_id" name="user_id"
                                    value="{{ $page->user->id }}" required readonly>
                                <input type="text" class="form-control" id="status" name="status"
                                    value="{{ $page->status }}" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre de la página</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $page->name }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea class="form-control" id="description" name="description" rows="3" required>{{ $page->description }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="navbar_id" class="form-label">Navbar</label>
                                <select class="form-select" id="navbar_id" name="navbar_id">
                                    <option value="">Sin Navbar</option>
                                    @foreach ($navbars as $navbar)
                                        <option value="{{ $navbar->id }}"
                                            {{ $page->navbar_id == $navbar->id ? 'selected' : '' }}>
                                            {{ $navbar->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="footer_id" class="form-label">Footer</label>
                                <select class="form-select" id="footer_id" name="footer_id">
                                    <option value="">Sin Footer</option>
                                    @foreach ($footers as $footer)
                                        <option value="{{ $footer->id }}"
                                            {{ $page->footer_id == $footer->id ? 'selected' : '' }}>
                                            {{ $footer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            Personalización
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="template_id" class="form-label">Plantilla</label>
                                <select class="form-select" id="template_id" name="template_id">
                                    <option value="">Seleccione un template</option>
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->id }}"
                                            {{ $page->template_id == $template->id ? 'selected' : '' }}>
                                            {{ $template->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="custom_html" class="form-label">HTML</label>
                                    <button type="button" id="format-html" class="btn btn-secondary btn-sm">Formatear
                                        HTML</button>
                                </div>
                                <textarea class="form-control" id="custom_html" name="custom_html" rows="5">{{ $page->content }}</textarea>
                            </div>
                            <button id="save" type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Reusable Modal -->
    <div class="modal fade" id="reusableModal" tabindex="-1" aria-labelledby="reusableModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reusableModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="reusableModalBody"></div>
                <div class="modal-footer" id="reusableModalFooter"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify-html.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editor;
            var reusableModal;

            function initializeCodeMirror() {
                var htmlContent = document.getElementById("custom_html").value;
                var formattedHTML = html_beautify(htmlContent, {
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

                editor = CodeMirror.fromTextArea(document.getElementById("custom_html"), {
                    lineNumbers: true,
                    mode: "htmlmixed",
                    theme: "monokai",
                    lineWrapping: true,
                    viewportMargin: Infinity
                });

                editor.setValue(formattedHTML);

                editor.on("change", function() {
                    document.getElementById("custom_html").value = editor.getValue();
                });
            }

            function initializeModal() {
                var modalElement = document.getElementById('reusableModal');
                if (modalElement) {
                    reusableModal = new bootstrap.Modal(modalElement);
                } else {
                    console.error('Modal element not found');
                }
            }

            function showModal(title, body, footer) {
                if (!reusableModal) {
                    console.error('Modal not initialized');
                    return;
                }

                document.getElementById('reusableModalLabel').textContent = title;
                document.getElementById('reusableModalBody').innerHTML = body;
                document.getElementById('reusableModalFooter').innerHTML = footer;

                reusableModal.show();
            }

            function showConfirmationModal(message, onConfirm) {
                showModal(
                    'Confirmar acción',
                    message,
                    `
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="modalConfirmButton">Confirmar</button>
                    `
                );

                document.getElementById('modalConfirmButton').onclick = function() {
                    reusableModal.hide();
                    onConfirm();
                };
            }

            function showSuccessModal(message, onClose) {
                showModal(
                    'Éxito',
                    message,
                    '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>'
                );

                if (onClose) {
                    document.getElementById('reusableModal').addEventListener('hidden.bs.modal', onClose, {
                        once: true
                    });
                }
            }

            function showErrorModal(message) {
                showModal(
                    'Error',
                    message,
                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>'
                );
            }

            function handleFormSubmit(e) {
                e.preventDefault();
                const selectedVersion = document.getElementById('version-select').value;
                const currentVersion = '{{ $page->version }}';

                let confirmMessage = '¿Estás seguro de que deseas guardar los cambios?';
                if (selectedVersion !== currentVersion) {
                    confirmMessage =
                        `Estás editando la versión ${selectedVersion}. Los cambios se guardarán como una actualización de la versión actual (${currentVersion}). ¿Deseas continuar?`;
                }

                showConfirmationModal(confirmMessage, submitForm);
            }

            function submitForm() {
                var formData = new FormData(document.getElementById('page-form'));
                const currentVersion = '{{ $page->version }}';
                const selectedVersion = document.getElementById('version-select').value;

                formData.append('update_source', 'show_view');
                formData.append('version_type', 'current');
                formData.set('content', editor.getValue());
                formData.set('selected_version', selectedVersion);

                fetch('{{ route('pages.update', $page->id) }}', {
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
                            showSuccessModal('Página actualizada exitosamente', () => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Error al actualizar la página');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorModal(error.message);
                    });
            }

            function handleSetAsCurrent() {
                const selectedVersion = document.getElementById('version-select').value;
                const currentVersion = '{{ $page->version }}';

                if (selectedVersion === currentVersion) {
                    showErrorModal('Esta ya es la versión actual.');
                    return;
                }

                showConfirmationModal(
                    `¿Estás seguro de que quieres establecer la versión ${selectedVersion} como la versión actual?`,
                    () => setVersionAsCurrent(selectedVersion)
                );
            }

            function setVersionAsCurrent(version) {
                fetch('{{ route('pages.update', $page->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            update_source: 'show_view',
                            version_type: 'set_current',
                            selected_version: version
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessModal('Versión establecida como actual exitosamente', () => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        showErrorModal(error.message);
                    });
            }

            function handleVersionChange() {
                const selectedVersion = this.value;
                const currentVersion = '{{ $page->version }}';
                const deleteButton = document.getElementById('delete-version');

                // Update delete button state
                deleteButton.disabled = selectedVersion === currentVersion ||
                    selectedVersion === '1.0.0' ||
                    document.getElementById('version-select').options.length <= 2;

                document.getElementById('selected-version').value = selectedVersion;

                fetch(`{{ route('pages.get-version', $page->id) }}?version=${selectedVersion}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateFormFields(data.page);
                        } else {
                            throw new Error('Error al cargar la versión seleccionada');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorModal(error.message);
                    });
            }

            function handleDeleteVersion() {
                const selectedVersion = document.getElementById('version-select').value;
                const currentVersion = '{{ $page->version }}';

                if (selectedVersion === currentVersion) {
                    showErrorModal('No puedes eliminar la versión actual.');
                    return;
                }

                if (selectedVersion === '1.0.0') {
                    showErrorModal('No puedes eliminar la versión inicial.');
                    return;
                }

                if (document.getElementById('version-select').options.length <= 2) {
                    showErrorModal('Debe existir al menos una versión además de la actual.');
                    return;
                }

                showConfirmationModal(
                    `¿Estás seguro de que quieres eliminar la versión ${selectedVersion}?`,
                    () => deleteVersion(selectedVersion)
                );
            }

            function deleteVersion(version) {
                fetch('{{ route('pages.deleteVersion', $page->id) }}', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            version
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showSuccessModal('Versión eliminada exitosamente', () => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showErrorModal(error.message);
                    });
            }

            function updateFormFields(pageData) {
                // Update form fields with pageData
                document.getElementById('name').value = pageData.name;
                document.getElementById('description').value = pageData.description;
                editor.setValue(pageData.content);
                document.getElementById('template_id').value = pageData.template_id;

                // Update other fields as necessary
                document.getElementById('page-slug').textContent = pageData.slug;
                document.getElementById('author-name').textContent = pageData.user.name;

                // Update thumbnail preview if available
                var thumbnailPreview = document.getElementById('thumbnail-preview');
                if (thumbnailPreview && pageData.thumbnail) {
                    thumbnailPreview.src = pageData.thumbnail;
                }
            }

            function formatHTML() {
                var currentContent = editor.getValue();
                var formattedContent = html_beautify(currentContent, {
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
                editor.setValue(formattedContent);

                // Mostrar modal de confirmación
                showSuccessModal('El HTML ha sido formateado correctamente.');
            }

            // Initialize components
            initializeCodeMirror();
            initializeModal();

            // Set up event listeners
            document.getElementById('page-form').addEventListener('submit', handleFormSubmit);
            document.getElementById('version-select').addEventListener('change', handleVersionChange);
            document.getElementById('delete-version').addEventListener('click', handleDeleteVersion);
            document.getElementById('format-html').addEventListener('click', formatHTML);
            document.getElementById('set-as-current').addEventListener('click', handleSetAsCurrent);

            // Handle thumbnail preview
            document.getElementById('thumbnail').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('thumbnail-preview').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
