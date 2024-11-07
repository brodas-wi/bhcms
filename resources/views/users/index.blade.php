@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">{{ __('Lista de Usuarios') }}</h3>
                    </div>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            @if (Auth::user()->hasRole('admin') || Auth::user()->can('create_user'))
                                <a href="{{ route('users.create') }}" class="btn btn-primary">{{ __('Crear usuario') }}</a>
                            @else
                                <a href="{{ route('users.create') }}" class="btn btn-primary disabled"
                                    aria-disabled="true">{{ __('Crear usuario') }}</a>
                            @endif

                            <form action="{{ route('users.index') }}" class="d-flex align-items-center" method="GET"
                                id="pagination-form">
                                <label for="perPage" class="me-2">Mostrar</label>
                                <select name="perPage" id="perPage" class="form-select form-select-sm"
                                    style="width: auto;" onchange="document.getElementById('pagination-form').submit();">
                                    <option value="5" {{ request('perPage') == 5 ? 'selected' : '' }}>5</option>
                                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                                </select>
                            </form>
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
                                        <th>Rol</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>
                                                @foreach ($user->roles as $role)
                                                    <span class="badge bg-secondary">{{ $role->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('edit_user'))
                                                        @if ($user->roles->first()->name !== 'admin')
                                                            <a href="{{ route('users.edit', $user->id) }}"
                                                                class="btn btn-outline-primary btn-sm">Editar</a>
                                                        @endif
                                                    @else
                                                        <a href="{{ route('users.edit', $user->id) }}"
                                                            class="btn btn-outline-primary btn-sm disabled"
                                                            aria-disabled="true">Editar</a>
                                                    @endif

                                                    @if (Auth::user()->hasRole('admin') || Auth::user()->can('delete_user'))
                                                        @if ($user->roles->first()->name !== 'admin')
                                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                                data-user-id="{{ $user->id }}">
                                                                Eliminar
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->appends(['perPage' => request('perPage')])->links() }}
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que quieres eliminar este usuario?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" action="" method="POST" style="display:inline-block;">
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

        .btn-group .btn {
            border-radius: 0.25rem;
        }

        .btn-group .btn:not(:last-child) {
            margin-right: 0.25rem;
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-item .page-link {
            color: #0154b8;
            background-color: #ffffff;
            border-color: #dee2e6;
        }

        .pagination .page-item.active .page-link {
            background-color: #0154b8;
            border-color: #0154b8;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .alert {
            border-radius: 0.25rem;
        }

        .badge {
            font-weight: 500;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = document.getElementById('deleteModal');
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var userId = button.getAttribute('data-user-id');
                var form = deleteModal.querySelector('#deleteForm');
                form.action = '/users/' + userId;
            });
        });
    </script>
@endpush
