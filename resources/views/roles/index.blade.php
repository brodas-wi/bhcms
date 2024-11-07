@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">{{ __('Gestión de roles') }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (Auth::user()->hasRole('admin') || Auth::user()->can('create_role'))
                                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                    Crear Rol
                                </a>
                            @else
                                <a href="{{ route('roles.create') }}" class="btn btn-primary disabled" aria-disabled="true">
                                    Crear Rol
                                </a>
                            @endif
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table-hover table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ $role->id }}</td>
                                            <td>{{ $role->name }}</td>
                                            <td>
                                                @if (Auth::user()->hasRole('admin') || Auth::user()->can('edit_role'))
                                                    @if ($role->name !== 'admin')
                                                        <a href="{{ route('roles.permissions.edit', $role->id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            {{ __('Editar Permisos') }}
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="confirmDelete('{{ $role->id }}', '{{ $role->name }}')">
                                                            {{ __('Eliminar') }}
                                                        </button>
                                                    @endif
                                                @else
                                                    <a href="{{ route('roles.permissions.edit', $role->id) }}"
                                                        class="btn btn-sm btn-outline-primary disabled"
                                                        aria-disabled="true">
                                                        {{ __('Editar permisos') }}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar el rol <span id="roleNameToDelete"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteRoleForm" action="" method="POST">
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
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(roleId, roleName) {
            document.getElementById('roleNameToDelete').textContent = roleName;
            document.getElementById('deleteRoleForm').action = `/roles/${roleId}`;
            var deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteConfirmModal.show();
        }
    </script>
@endpush
