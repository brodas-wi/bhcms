{{-- contents/trash.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 mb-0">Papelera de Contenidos</h2>
            <a href="{{ route('contents.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Volver al Listado
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                @if ($trashedContents->isEmpty())
                    <div class="py-5 text-center">
                        <i class="fas fa-trash text-muted mb-3" style="font-size: 3rem;"></i>
                        <p class="h5 text-muted">La papelera está vacía</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table-hover table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" width="80">Imagen</th>
                                    <th scope="col">Título</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Eliminado</th>
                                    <th scope="col" class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trashedContents as $content)
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
                                        <td>{{ $content->deleted_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-success"
                                                    data-bs-toggle="modal" data-bs-target="#restoreModal"
                                                    data-content-id="{{ $content->id }}"
                                                    data-content-title="{{ $content->title }}" title="Restaurar contenido">
                                                    <i class="fas fa-trash-restore"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal" data-bs-target="#forceDeleteModal"
                                                    data-content-id="{{ $content->id }}"
                                                    data-content-title="{{ $content->title }}"
                                                    title="Eliminar permanentemente">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $trashedContents->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de Restauración -->
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Confirmar Restauración</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro que desea restaurar el contenido "<span id="restoreContentTitle"></span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <form id="restoreForm" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Restaurar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Eliminación Permanente -->
    <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forceDeleteModalLabel">Confirmar Eliminación Permanente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Esta acción no se puede deshacer.
                    </div>
                    ¿Está seguro que desea eliminar permanentemente el contenido "<span
                        id="forceDeleteContentTitle"></span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-cancelar" data-bs-dismiss="modal">Cancelar</button>
                    <form id="forceDeleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Eliminar Permanentemente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Modal de Restauración
                const restoreModal = document.getElementById('restoreModal');
                restoreModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const contentId = button.getAttribute('data-content-id');
                    const contentTitle = button.getAttribute('data-content-title');

                    document.getElementById('restoreContentTitle').textContent = contentTitle;
                    document.getElementById('restoreForm').action = `/contents/${contentId}/restore`;
                });

                // Modal de Eliminación Permanente
                const forceDeleteModal = document.getElementById('forceDeleteModal');
                forceDeleteModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const contentId = button.getAttribute('data-content-id');
                    const contentTitle = button.getAttribute('data-content-title');

                    document.getElementById('forceDeleteContentTitle').textContent = contentTitle;
                    document.getElementById('forceDeleteForm').action = `/contents/${contentId}/force-delete`;
                });
            });
        </script>
    @endpush

@endsection
