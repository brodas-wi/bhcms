@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0">Gestión de Contenidos</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('contents.trash') }}" class="btn btn-outline-white">
                    <i class="fas fa-trash me-2"></i>Papelera
                </a>
                <a href="{{ route('contents.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Crear Nuevo Contenido
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table-hover table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" width="80">Imagen</th>
                                <th scope="col">Título</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Fecha de Publicación</th>
                                <th scope="col" class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contents as $content)
                                <tr>
                                    <td>
                                        @if ($content->featured_image)
                                            <img src="{{ $content->featured_image }}" class="img-thumbnail"
                                                alt="Imagen de {{ $content->title }}"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <h6 class="mb-0">{{ $content->title }}</h6>
                                    </td>
                                    <td>
                                        <small class="text-muted text-break">{{ $content->slug }}</small>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ ucfirst($content->type) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'warning',
                                                'published' => 'success',
                                                'scheduled' => 'info',
                                            ];
                                            $statusTexts = [
                                                'draft' => 'Borrador',
                                                'published' => 'Publicado',
                                                'scheduled' => 'Programado',
                                            ];
                                            $statusColor = $statusColors[$content->status] ?? 'secondary';
                                            $statusText = $statusTexts[$content->status] ?? ucfirst($content->status);
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }} rounded-pill">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>{{ $content->published_at?->format('d/m/Y H:i') ?? 'No publicado' }}</td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a href="{{ route('contents.edit', $content) }}"
                                                class="btn btn-sm btn-outline-primary" title="Editar contenido">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                data-content-id="{{ $content->id }}"
                                                data-content-title="{{ $content->title }}" title="Eliminar contenido">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $contents->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro que desea eliminar el contenido "<span id="contentTitle"></span>"?
                    Esta acción no se puede deshacer.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const deleteModal = document.getElementById('deleteModal');
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const contentId = button.getAttribute('data-content-id');
                    const contentTitle = button.getAttribute('data-content-title');

                    document.getElementById('contentTitle').textContent = contentTitle;
                    document.getElementById('deleteForm').action = `/contents/${contentId}`;
                });
            });
        </script>
    @endpush
@endsection
