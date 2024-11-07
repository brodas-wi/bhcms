@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">{{ __('Gestión de permisos') }}</h3>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @if (Auth::user()->hasRole('admin') || Auth::user()->can('create_permission'))
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary">Crear Permiso</a>
                        @else
                            <a href="{{ route('permissions.create') }}" class="btn btn-primary disabled" aria-disabled="true">Crear Permiso</a>
                        @endif

                        <form action="{{ route('permissions.index') }}" method="GET" id="pagination-form" class="d-flex align-items-center">
                            <label for="perPage" class="me-2">Mostrar</label>
                            <select name="perPage" id="perPage" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </form>
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

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Guard Name</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->name }}</td>
                                        <td>{{ $permission->guard_name }}</td>
                                        <td>
                                            @if (Auth::user()->hasRole('admin') || Auth::user()->can('delete_permission'))
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                        onclick="confirmDelete('{{ $permission->id }}', '{{ $permission->name }}')">
                                                    Eliminar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $permissions->appends(['perPage' => request('perPage')])->links() }}
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary">
                            {{ __('Regresar') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar el permiso <span id="permissionNameToDelete"></span>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deletePermissionForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    .card-header {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .table th {
        font-weight: 600;
    }
    .btn-outline-primary {
        color: #0154b8;
        border-color: #0154b8;
    }
    .btn-outline-primary:hover {
        background-color: #0154b8;
        color: white;
    }
    .btn-primary {
        background-color: #0154b8;
        border-color: #0154b8;
    }
    .btn-primary:hover {
        background-color: #004085;
        border-color: #004085;
    }
    .pagination {
        margin-bottom: 0;
    }
    .pagination .page-item .page-link {
        color: #0154b8;
    }
    .pagination .page-item.active .page-link {
        background-color: #0154b8;
        border-color: #0154b8;
    }
</style>
@endpush

@push('scripts')
<script>
    function confirmDelete(permissionId, permissionName) {
        document.getElementById('permissionNameToDelete').textContent = permissionName;
        document.getElementById('deletePermissionForm').action = `/permissions/${permissionId}`;
        var deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
        deleteConfirmModal.show();
    }
</script>
@endpush
