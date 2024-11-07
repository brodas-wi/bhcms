@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row g-4">
            <!-- Barra lateral (filtros y acciones) -->
            <div class="col-lg-3 mt-0">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Gestión de Plantillas</h2>
                        <a href="{{ route('templates.create') }}" class="btn btn-primary w-100 mb-4">
                            <i class="fas fa-plus-circle me-2"></i>Nueva Plantilla
                        </a>
                        <h3 class="h5 mb-3">Filtros</h3>
                        <form action="{{ route('templates.index') }}" method="GET">
                            <div class="mb-3">
                                <label for="type" class="form-label">Tipo</label>
                                <select class="form-select form-select-sm" id="type" name="type">
                                    <option value="">Todos</option>
                                    <option value="page">Página</option>
                                    <option value="article">Artículo</option>
                                    <option value="blog">Blog</option>
                                    <option value="news">Noticia</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="layout" class="form-label">Diseño</label>
                                <select class="form-select form-select-sm" id="layout" name="layout">
                                    <option value="">Todos</option>
                                    <option value="one_column">Una Columna</option>
                                    <option value="two_columns">Dos Columnas</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Categoría</label>
                                <select class="form-select form-select-sm" id="category" name="category">
                                    <option value="">Todas</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}"
                                            {{ old('category') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                <i class="fas fa-filter me-2"></i>Aplicar Filtros
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="col-lg-9 mt-0">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h2 class="h4 mb-0">Plantillas Disponibles</h2>
                    </div>
                    <div class="card-body">
                        @if ($templates->isEmpty())
                            <div class="py-5 text-center">
                                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                                <h3 class="h5">No hay plantillas creadas</h3>
                                <p class="text-muted">Comienza creando tu primera plantilla haciendo clic en "Nueva
                                    Plantilla".</p>
                            </div>
                        @else
                            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                                @foreach ($templates as $template)
                                    <div class="col">
                                        <div class="card card-small h-100 border-0 shadow-sm">
                                            <img src="{{ $template->thumbnail }}" class="card-img-top"
                                                alt="{{ $template->name }}" style="height: 160px; object-fit: cover;">
                                            <div class="card-body d-flex flex-column">
                                                <h3 class="h5 card-title mb-2">{{ $template->name }}</h3>
                                                <p class="card-text text-muted small mb-2">Creado:
                                                    {{ $template->created_at->format('d/m/Y') }}</p>
                                                <p class="card-text flex-grow-1">
                                                    {{ Str::limit($template->description, 80) }}</p>
                                                <div class="d-flex justify-content-between align-items-center mt-3">
                                                    @if ($template->is_default)
                                                        <span class="badge bg-primary">Por defecto</span>
                                                    @else
                                                        <span class="badge bg-secondary">Personalizada</span>
                                                    @endif
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('templates.show', $template->id) }}"
                                                            class="btn btn-outline-primary btn-sm" title="Ver detalles">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('templates.edit', $template->id) }}"
                                                            class="btn btn-outline-warning btn-sm edit-template"
                                                            title="Editar" data-template-id="{{ $template->id }}">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @if (!$template->is_default)
                                                            <form action="{{ route('templates.destroy', $template->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta plantilla?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                    title="Eliminar">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this modal at the end of your content section -->
    <div class="modal fade" id="selectPageModal" tabindex="-1" aria-labelledby="selectPageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectPageModalLabel">Seleccionar página</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select id="pageSelector" class="form-select">
                        <option value="">Seleccionar</option>
                        @foreach ($pages as $page)
                            <option value="{{ $page->id }}">{{ $page->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="confirmPageSelection">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        @media (max-width: 991.98px) {
            .col-lg-3 {
                order: -1;
            }
        }

        .card-small {
            transition: transform 0.2s;
        }

        .card-small:hover {
            transform: translateY(-5px);
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.querySelectorAll('.btn-outline-warning').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const templateId = this.dataset.templateId;
                console.log('Template ID:', templateId);
                const modal = new bootstrap.Modal(document.getElementById('selectPageModal'));
                modal.show();

                document.getElementById('confirmPageSelection').onclick = function() {
                    const selectedPageId = document.getElementById('pageSelector').value;
                    const url =
                        `/templates/${templateId}/edit${selectedPageId ? `?page_id=${selectedPageId}` : ''}`;
                    console.log('Redirect URL:', url);
                    window.location.href = url;
                };
            });
        });
    </script>
@endpush
