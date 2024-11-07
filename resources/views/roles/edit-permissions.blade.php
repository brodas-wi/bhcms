@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h3 class="card-header bg-light">
                        {{ __('Gestionar permisos de:') }} <strong>{{ $role->name }}</strong>
                    </h3>

                    <div class="card-body">
                        <!-- Formulario para agregar permisos -->
                        <form method="POST" id="permissionsForm" action="{{ route('roles.permissions.update', $role->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- Select list to get all permissions --}}
                            {{-- List to make array for permissions update --}}
                            <div style="max-height: 400px; overflow-y: auto;" class="my-2">
                                {{-- Unordered list to add permissions --}}
                                <ul id="selected-permissions" class="list-group">
                                    @foreach ($permissions as $permission)
                                        <div class="list-group-item d-flex justify-content-between list-group-item-action"
                                            onclick="toggleCheckbox('permission{{ $permission->id }}')">
                                            <input id="permission{{ $permission->id }}" type="checkbox" name="permissions[]"
                                                value="{{ $permission->id }}"
                                                {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <label>{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Button from group --}}
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                                    {{ __('Regresar') }}
                                </a>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#confirmModal">
                                    {{ __('Actualizar Permisos') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Actualización de Permisos</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea actualizar los permisos para el rol <strong>{{ $role->name }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="submitForm()">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleCheckbox(id) {
            const checkbox = document.getElementById(id);
            checkbox.checked = !checkbox.checked;
        }

        function submitForm() {
            document.getElementById('permissionsForm').submit();
        }
    </script>
@endpush

@push('styles')
    <style>
        .btn-outline-primary {
            color: #0154b8;
            border-color: #0154b8;
        }

        .btn-outline-primary:hover {
            background-color: #0154b8;
            color: white;
        }
    </style>
@endpush
