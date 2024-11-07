<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Editar Contenido</title>

        <!-- Primero cargar todos los estilos -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://unpkg.com/grapesjs@0.21.2/dist/css/grapes.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <link href="https://unpkg.com/dropzone@5.9.3/dist/min/dropzone.min.css" rel="stylesheet">

        <style>
            .js-loading {
                visibility: hidden;
            }

            /* Editor principal y paneles */
            .gjs-one-bg {
                background-color: #2c3e50;
            }

            .gjs-two-color {
                color: #ffffff;
            }

            .gjs-three-bg {
                background-color: #34495e;
            }

            .gjs-four-color {
                color: #718093;
            }

            #gjs {
                border: 3px solid #444;
                height: calc(100vh - 200px);
            }

            /* Panel de bloques */
            .gjs-blocks-c {
                height: 100%;
                overflow-y: auto;
            }

            /* Panel de estilos */
            .gjs-pn-panel {
                padding: 0;
            }

            .gjs-pn-panel.gjs-pn-devices-c {
                padding: 0 5px;
            }

            .gjs-devices-c button {
                margin: 0 5px;
            }

            /* Botones primary */
            .btn-primary {
                background-color: #0154b8 !important;
                border-color: #0154b8 !important;
            }

            .btn-primary:hover {
                background-color: #014397 !important;
                border-color: #014397 !important;
            }

            .btn-primary:focus {
                box-shadow: 0 0 0 0.25rem rgba(1, 84, 184, 0.25) !important;
            }

            /* Botones outline */
            .btn-outline-primary {
                color: #0154b8 !important;
                border-color: #0154b8 !important;
            }

            .btn-outline-primary:hover {
                color: #fff !important;
                background-color: #0154b8 !important;
            }

            /* Links y texto */
            .text-primary {
                color: #0154b8 !important;
            }

            a {
                color: #0154b8;
            }

            a:hover {
                color: #014397;
            }

            /* Inputs, checks y otros elementos de formulario */
            .form-check-input:checked {
                background-color: #0154b8 !important;
                border-color: #0154b8 !important;
            }

            .form-control:focus,
            .form-select:focus {
                border-color: #0154b8 !important;
                box-shadow: 0 0 0 0.25rem rgba(1, 84, 184, 0.25) !important;
            }

            /* Backgrounds */
            .bg-primary {
                background-color: #0154b8 !important;
            }

            /* Progress bars y otros elementos */
            .progress-bar {
                background-color: #0154b8 !important;
            }

            /* Badges */
            .badge.bg-primary {
                background-color: #0154b8 !important;
            }

            /* Bordes */
            .border-primary {
                border-color: #0154b8 !important;
            }

            /* Dropdown items */
            .dropdown-item.active,
            .dropdown-item:active {
                background-color: #0154b8 !important;
            }

            /* Nav pills y tabs */
            .nav-pills .nav-link.active,
            .nav-pills .show>.nav-link {
                background-color: #0154b8 !important;
            }

            .nav-tabs .nav-link {
                color: #0154b8 !important;
            }

            .nav-tabs .nav-link.active {
                color: #0154b8 !important;
                border-color: #0154b8 !important;
            }

            /* Layout principal */
            .editor-row {
                display: flex;
                justify-content: flex-start;
                align-items: stretch;
                flex-wrap: nowrap;
                height: calc(100vh - 200px);
            }

            .editor-canvas {
                flex-grow: 1;
                min-width: 0;
            }

            .panel-sidebar {
                width: 280px;
                margin-left: 15px;
                background-color: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 4px;
                overflow-y: auto;
            }

            .panel-sidebar .nav-tabs {
                position: sticky;
                top: 0;
                background: white;
                z-index: 1;
            }

            .dropzone {
                border: 2px dashed #0087F7;
                border-radius: 5px;
                background: white;
                min-height: 150px;
                padding: 20px;
                position: relative;
                cursor: pointer;
            }

            .dropzone .dz-message {
                text-align: center;
                margin: 2em 0;
            }

            .dropzone .dz-message .dz-button {
                background: none;
                color: #666;
                border: none;
                padding: 0;
                font: inherit;
                cursor: pointer;
                outline: inherit;
            }

            .dropzone .dz-preview {
                margin: 10px;
            }

            .dropzone .dz-preview .dz-image {
                border-radius: 4px;
            }

            .upload-info {
                background-color: #f8f9fa;
                border-radius: 4px;
            }

            .upload-info ul {
                padding-left: 1.2rem;
            }

            .recent-uploads {
                max-height: 200px;
                overflow-y: auto;
            }

            /* Estilo para el scroll en recent-uploads */
            .recent-uploads::-webkit-scrollbar {
                width: 6px;
            }

            .recent-uploads::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }

            .recent-uploads::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 3px;
            }

            .recent-uploads::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid py-4">
            <!-- Barra superior -->
            <div class="row mb-4">
                <div class="col">
                    <h2>Editar Contenido</h2>
                </div>
                <div class="col text-end">
                    <div class="btn-group me-2">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                            data-bs-toggle="dropdown">
                            <i class="fas fa-history"></i> Versiones
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ($versions as $version)
                                <li>
                                    <a class="dropdown-item" href="#"
                                        onclick="restoreVersion({{ $version->id }})">
                                        Versión {{ $version->version_number }} -
                                        {{ $version->created_at->format('d/m/Y H:i') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <button class="btn btn-secondary me-2" id="btnPreview">
                        <i class="fas fa-eye"></i> Previsualizar
                    </button>
                    <button class="btn btn-primary" id="btnSave">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </div>

            <form id="contentForm" action="{{ route('contents.update', $content->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="content" id="gjs-content" value="{{ $content->content }}">
                <input type="hidden" id="existing-category" value="{{ $content->categories->first()->id ?? '' }}">
                <input type="hidden" id="existing-tags" value="{{ json_encode($content->tags->pluck('id')) }}">
                <input type="hidden" id="categories-data" value="{{ json_encode($categories) }}">
                <input type="hidden" id="tags-data" value="{{ json_encode($tags) }}">

                <!-- Sección de Formulario -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <!-- Título y Slug -->
                                <div class="row mb-3">
                                    <div class="col-md-8">
                                        <label for="title" class="form-label">Título</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ $content->title }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="slug" class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            value="{{ $content->slug }}" readonly>
                                    </div>
                                </div>

                                <!-- Tipo y Estado -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="type" class="form-label">Tipo de contenido</label>
                                        <select class="form-select" id="type" name="type" required>
                                            <option value="article"
                                                {{ $content->type == 'article' ? 'selected' : '' }}>
                                                Artículo</option>
                                            <option value="blog" {{ $content->type == 'blog' ? 'selected' : '' }}>
                                                Blog
                                            </option>
                                            <option value="news" {{ $content->type == 'news' ? 'selected' : '' }}>
                                                Noticia
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Estado</label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="draft" {{ $content->status == 'draft' ? 'selected' : '' }}>
                                                Borrador
                                            </option>
                                            <option value="published"
                                                {{ $content->status == 'published' ? 'selected' : '' }}>
                                                Publicar</option>
                                            <option value="scheduled"
                                                {{ $content->status == 'scheduled' ? 'selected' : '' }}>
                                                Programado</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Categorías y Tags -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Categorías</label>
                                        <div class="input-group">
                                            <div id="categoriesContainer" class="form-control overflow-auto"
                                                style="min-height: 38px; max-height: 76px">
                                                <!-- Categories will be populated here -->
                                            </div>
                                            <button type="button" class="btn btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#categoriesModal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Etiquetas</label>
                                        <div class="input-group">
                                            <div id="tagsContainer" class="form-control overflow-auto"
                                                style="min-height: 38px; max-height: 76px">
                                                <!-- Tags will be populated here -->
                                            </div>
                                            <button type="button" class="btn btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#tagsModal">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Imagen destacada -->
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <label class="form-label">Imagen destacada</label>
                                        <button type="button" class="btn btn-outline-primary w-100"
                                            data-bs-toggle="modal" data-bs-target="#mediaModal">
                                            <i class="fas fa-image"></i> Seleccionar imagen destacada
                                        </button>
                                    </div>
                                    <div class="col-12">
                                        <div id="featuredImagePreview" class="bg-light rounded p-3 text-center">
                                            <img src="{{ $content->featured_image ?? '/api/placeholder/400/300' }}"
                                                class="img-fluid rounded" style="max-height: 200px; width: auto;"
                                                alt="Vista previa">
                                        </div>
                                        <input type="hidden" name="featured_image" id="featured_image"
                                            value="{{ $content->featured_image }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Panel lateral de SEO y extracto -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-search me-2"></i>SEO y Extracto
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="excerpt" class="form-label">Extracto
                                        <small class="text-muted">(Resumen corto del contenido)</small>
                                    </label>
                                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3">{{ $content->excerpt }}</textarea>
                                    <div class="form-text">Este texto se mostrará en las vistas previas y resultados de
                                        búsqueda</div>
                                </div>

                                <hr>

                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Título SEO
                                        <small class="text-muted">(50-60 caracteres)</small>
                                    </label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                        maxlength="60" value="{{ $content->meta_title }}">
                                    <div class="form-text">Si se deja vacío, se usará el título principal</div>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Descripción
                                        <small class="text-muted">(150-160 caracteres)</small>
                                    </label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3" maxlength="160">{{ $content->meta_description }}</textarea>
                                    <div class="form-text">Si se deja vacío, se usará el extracto</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección del Editor -->
                <div class="row">
                    <div class="col-12">
                        <div class="editor-row">
                            <div class="panel-sidebar">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#blocks-panel">
                                            <i class="fas fa-th-large"></i> Bloques
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#styles-panel">
                                            <i class="fas fa-paint-brush"></i> Estilos
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content p-3">
                                    <div class="tab-pane fade show active" id="blocks-panel">
                                        <div id="blocks"></div>
                                    </div>
                                    <div class="tab-pane fade" id="styles-panel">
                                        <div class="styles-container"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="editor-canvas">
                                <div id="gjs"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal de Categorías -->
        <div class="modal fade" id="categoriesModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Seleccionar Categorías</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="categoriesList">
                            <!-- Categories will be populated here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveCategoriesBtn">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Tags -->
        <div class="modal fade" id="tagsModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Administrar Etiquetas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input type="text" class="form-control" id="newTag"
                                placeholder="Agregar nueva etiqueta">
                            <button class="btn btn-outline-primary mt-2" id="addNewTagBtn">
                                <i class="fas fa-plus"></i> Agregar
                            </button>
                        </div>
                        <div id="tagsList">
                            <!-- Tags will be populated here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="saveTagsBtn">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Medios -->
        <div class="modal fade" id="mediaModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Medios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="nav nav-tabs mb-3">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#uploadTab">
                                    <i class="fas fa-cloud-upload-alt"></i> Subir
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#libraryTab">
                                    <i class="fas fa-photo-video"></i> Librería
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <!-- Tab de Subida -->
                            <div id="uploadTab" class="tab-pane fade show active">
                                <div class="upload-container">
                                    <form id="mediaDropzone" class="dropzone needsclick" action="/upload-image">
                                        @csrf
                                        <div class="dz-message needsclick">
                                            <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                            <h4>Arrastra los archivos o haz click aquí</h4>
                                            <span class="text-muted">Solo imágenes (JPG, PNG, GIF)</span>
                                        </div>
                                    </form>

                                    <div class="upload-info mt-3">
                                        <div class="alert alert-info">
                                            <h6 class="mb-2"><i class="fas fa-info-circle"></i> Información de
                                                subida</h6>
                                            <ul class="small mb-0">
                                                <li>Tipos permitidos: JPG, PNG, GIF</li>
                                                <li>Tamaño máximo: 5MB</li>
                                                <li>Dimensión recomendada: 1200x800 pixels</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="recent-uploads mt-3">
                                        <h6 class="border-bottom pb-2">Subidas Recientes</h6>
                                        <div class="row g-3" id="recentUploadsGrid">
                                            <!-- Las subidas recientes se mostrarán aquí dinámicamente -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab de Librería -->
                            <div id="libraryTab" class="tab-pane fade">
                                <!-- Filtros y Búsqueda -->
                                {{-- <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Buscar medios...">
                                        <button class="btn btn-outline-secondary" type="button">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div> --}}

                                <!-- Grid de medios -->
                                <div id="mediaGrid" class="row g-3">
                                    <!-- Los items de la librería se cargarán aquí -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="selectMediaBtn">Seleccionar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://unpkg.com/grapesjs@0.21.2/dist/grapes.min.js"></script>
        <script src="https://unpkg.com/grapesjs-blocks-basic@0.1.8/dist/grapesjs-blocks-basic.min.js"></script>
        <script src="https://unpkg.com/toastr@2.1.4/build/toastr.min.js"></script>
        <script src="https://unpkg.com/dropzone@5.9.3/dist/min/dropzone.min.js"></script>
        <script src="{{ asset('js/editor-content.js') }}"></script>
        <script src="{{ asset('js/media-upload.js') }}"></script>

        <!-- Función para restaurar versiones -->
        <script>
            function restoreVersion(versionId) {
                if (confirm(
                        '¿Estás seguro de que deseas restaurar esta versión? Se creará una nueva versión del contenido actual.'
                    )) {
                    fetch(`/contents/{{ $content->id }}/versions/${versionId}/restore`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                window.location.reload();
                            } else {
                                toastr.error(result.message || 'Error al restaurar la versión');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('Error al restaurar la versión');
                        });
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Función para actualizar contador de caracteres
                function updateCharCount(input, maxLength) {
                    const current = input.value.length;
                    const counter = input.parentElement.querySelector('.char-counter');
                    if (!counter) {
                        const counterDiv = document.createElement('small');
                        counterDiv.className = 'char-counter text-muted d-block mt-1';
                        input.parentElement.appendChild(counterDiv);
                    }
                    input.parentElement.querySelector('.char-counter').textContent =
                        `${current}/${maxLength} caracteres`;
                }

                // Agregar contadores a los campos SEO
                ['meta_title', 'meta_description'].forEach(id => {
                    const input = document.getElementById(id);
                    if (input) {
                        input.addEventListener('input', () => {
                            updateCharCount(input, input.maxLength);
                        });
                        // Inicializar contador
                        updateCharCount(input, input.maxLength);
                    }
                });
            });
        </script>
    </body>

</html>
