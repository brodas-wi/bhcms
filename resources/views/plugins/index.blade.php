@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="w-100 d-flex align-items-center justify-content-between">
            <h1 class="mb-4">Plugins</h1>
            <div class="mb-3">
                <a href="{{ route('plugins.create') }}" class="btn btn-primary">Crear Plugin</a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="table-responsive position-relative">
            <table class="table-striped table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Versión</th>
                        <th>Autor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($plugins as $plugin)
                        <tr>
                            <td clase="align-middle">{{ $plugin->original_name }}</td>
                            <td clase="align-middle">{{ $plugin->description }}</td>
                            <td clase="align-middle">{{ $plugin->version }}</td>
                            <td clase="align-middle">{{ $plugin->author }}</td>
                            <td clase="align-middle">
                                @if ($plugin->is_active)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td clase="align-middle">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button"
                                        id="dropdownMenuButton{{ $plugin->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Acciones
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $plugin->id }}">
                                        <li>
                                            @if ($plugin->is_active)
                                                <form action="{{ route('plugins.deactivate', $plugin) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-warning">
                                                        <i class="fas fa-pause-circle"></i> Desactivar
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('plugins.activate', $plugin) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-success">
                                                        <i class="fas fa-play-circle"></i> Activar
                                                    </button>
                                                </form>
                                            @endif
                                        </li>
                                        <li><a class="dropdown-item" href="{{ route('plugins.edit', $plugin) }}">
                                                <i class="fas fa-edit"></i> Editar
                                            </a></li>
                                        <li><a class="dropdown-item" href="{{ route('plugins.configure', $plugin) }}">
                                                <i class="fas fa-cog"></i> Configurar
                                            </a></li>
                                        @if ($plugin->is_active)
                                            @if (!empty($plugin->views))
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <h6 class="dropdown-header">Vistas del plugin:</h6>
                                                </li>
                                                @foreach ($plugin->views as $view)
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('plugins.preview', ['plugin' => $plugin, 'viewName' => $view]) }}"
                                                            target="_blank">
                                                            <i class="fas fa-eye"></i> {{ $view }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        @endif
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $plugin->id }}">
                                                <i class="fas fa-trash-alt"></i> Eliminar
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $plugin->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $plugin->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $plugin->id }}">Confirmar
                                                    eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de eliminar el plugin "{{ $plugin->original_name }}"?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <form action="{{ route('plugins.destroy', $plugin) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No se han encontrado plugins.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table-responsive {
            overflow: visible !important;
        }

        .action-dropdown {
            position: relative;
        }

        .action-dropdown .dropdown-menu {
            position: absolute;
            top: 100%;
            left: auto;
            right: 0;
            z-index: 1000;
            display: none;
            min-width: 10rem;
            padding: 0.5rem 0;
            margin: 0.125rem 0 0;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: 0.25rem;
        }

        .action-dropdown .dropdown-menu.show {
            display: block;
        }

        .dropdown-item:hover {
            background-color: #0154b8 !important;
            color: white !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dropdowns = document.querySelectorAll('.action-dropdown');

            dropdowns.forEach(function(dropdown, index) {
                var button = dropdown.querySelector('.dropdown-toggle');
                var menu = dropdown.querySelector('.dropdown-menu');

                if (button && menu) {
                    button.addEventListener('click', function(event) {
                        event.stopPropagation();
                        event.preventDefault();
                        toggleMenu(menu);
                    });
                }
            });

            function toggleMenu(menu) {
                var isOpen = menu.classList.contains('show');

                // Cerrar todos los otros menús abiertos
                document.querySelectorAll('.action-dropdown .dropdown-menu.show').forEach(function(openMenu) {
                    if (openMenu !== menu) {
                        openMenu.classList.remove('show');
                    }
                });

                menu.classList.toggle('show');

                // Ajustar la posición del menú si está fuera de la vista
                if (menu.classList.contains('show')) {
                    var rect = menu.getBoundingClientRect();
                    var windowHeight = window.innerHeight;
                    if (rect.bottom > windowHeight) {
                        menu.style.top = 'auto';
                        menu.style.bottom = '100%';
                    } else {
                        menu.style.top = '100%';
                        menu.style.bottom = 'auto';
                    }
                }
            }

            // Cerrar dropdown cuando se hace clic fuera
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.action-dropdown')) {
                    document.querySelectorAll('.action-dropdown .dropdown-menu.show').forEach(function(
                        openMenu) {
                        openMenu.classList.remove('show');
                    });
                }
            });
        });
    </script>
@endpush
