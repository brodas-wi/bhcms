@extends('layouts.app')

@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .card {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border: none;
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #e3e6f0;
        }

        .table> :not(caption)>*>* {
            padding: 1rem 1rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 2rem;
            width: 2rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .btn-icon i {
            font-size: 0.875rem;
        }

        .preview-container {
            border: 1px solid #dee2e6;
            padding: 1rem;
            border-radius: 0.375rem;
            background: #fff;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .status-badge.active {
            background-color: #d1fae5;
            color: #065f46;
            border-color: #34d399;
        }

        .status-badge.inactive {
            background-color: #fee2e2;
            color: #991b1b;
            border-color: #f87171;
        }

        .status-badge:hover {
            opacity: 0.8;
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
        }

        .navbar-title {
            font-size: 1.5rem;
            color: #1f2937;
            font-weight: 600;
        }

        #previewFrame {
            width: 100%;
            border: none;
            transition: height 0.3s ease;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center py-3">
                        <span class="navbar-title">Barras de Navegación</span>
                        <a href="{{ route('navbars.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            <span>Nueva Barra</span>
                        </a>
                    </div>
                    <div class="card-body px-0 pb-2">
                        @if ($navbars->isEmpty())
                            <div class="alert alert-info mx-4 text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                No hay barras de navegación creadas.
                            </div>
                        @else
                            <div class="table-responsive mx-4">
                                <table class="align-items-center mb-0 table">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Nombre</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Template</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Creación</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Estado</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($navbars->sortBy('id') as $navbar)
                                            <tr>
                                                <td>
                                                    <span
                                                        class="text-secondary font-weight-bold text-xs">{{ $navbar->id }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-secondary font-weight-bold text-xs">{{ $navbar->name }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-secondary font-weight-bold text-xs">{{ $navbar->template_id ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-secondary font-weight-bold text-xs">{{ $navbar->created_at->format('d/m/Y H:i') }}</span>
                                                </td>
                                                <td>
                                                    <button
                                                        class="status-badge {{ $navbar->is_active ? 'active' : 'inactive' }}"
                                                        id="status-btn-{{ $navbar->id }}"
                                                        data-navbar-id="{{ $navbar->id }}"
                                                        data-active="{{ $navbar->is_active ? 'true' : 'false' }}">
                                                        {{ $navbar->is_active ? 'Activo' : 'Inactivo' }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <button class="btn btn-icon btn-primary preview-btn"
                                                            data-content="{{ base64_encode($navbar->content) }}"
                                                            data-css="{{ base64_encode($navbar->css) }}" type="button">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <a href="{{ route('navbars.edit', $navbar) }}"
                                                            class="btn btn-icon btn-outline-warning" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-icon btn-outline-danger delete-btn"
                                                            data-navbar-id="{{ $navbar->id }}"
                                                            data-navbar-name="{{ $navbar->name }}" type="button">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    <form id="delete-form-{{ $navbar->id }}"
                                                        action="{{ route('navbars.destroy', $navbar) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Vista Previa -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vista Previa de la Barra de Navegación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <!-- El iframe se insertará aquí dinámicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar esta barra de navegación?</p>
                    <p class="text-danger fw-bold mb-0">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery primero -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Luego Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Configuración de Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };

            // Manejador para los botones de estado
            $('.status-badge').on('click', function() {
                const button = $(this);
                const navbarId = button.data('navbar-id');

                $.ajax({
                    url: `/navbars/${navbarId}/toggle-active`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            button.toggleClass('active inactive');
                            button.text(response.is_active ? 'Activo' : 'Inactivo');
                            toastr.success('Estado actualizado correctamente');
                        }
                    },
                    error: function() {
                        toastr.error('Error al actualizar el estado');
                    }
                });
            });

            // Manejador para la vista previa
            function decodeBase64(str) {
                try {
                    return decodeURIComponent(atob(str).split('').map(function(c) {
                        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                    }).join(''));
                } catch (e) {
                    console.error('Error decodificando:', e);
                    return '';
                }
            }

            $('.preview-btn').on('click', function() {
                const encodedContent = $(this).data('content');
                const encodedCss = $(this).data('css');

                // Decodificar el contenido y CSS
                const content = decodeBase64(encodedContent);
                const css = decodeBase64(encodedCss || '');

                const modal = new bootstrap.Modal(document.getElementById('previewModal'));
                modal.show();

                // Crear un nuevo iframe cada vez que se abre el modal
                const iframeContainer = document.querySelector('.modal-body');
                const oldIframe = document.getElementById('previewFrame');
                if (oldIframe) {
                    oldIframe.remove();
                }

                const iframe = document.createElement('iframe');
                iframe.id = 'previewFrame';
                iframe.style.width = '100%';
                iframe.style.height = '500px';
                iframe.style.border = 'none';
                iframe.style.backgroundColor = 'white';
                iframeContainer.appendChild(iframe);

                // Esperar a que el iframe esté listo
                setTimeout(() => {
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    const htmlContent = `
                        <!DOCTYPE html>
                        <html lang="es">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>Vista Previa</title>
                            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
                            <style>
                                body {
                                    margin: 0;
                                    padding: 0;
                                    min-height: 100vh;
                                    background-color: #ffffff;
                                }
                                ${css}
                            </style>
                        </head>
                        <body>
                            ${content}
                            <script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>
                            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"><\/script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    // Inicializar componentes de Bootstrap
                                    var dropdowns = document.querySelectorAll('.dropdown-toggle');
                                    dropdowns.forEach(function(dropdown) {
                                        new bootstrap.Dropdown(dropdown);
                                    });

                                    // Ajustar altura del iframe
                                    window.parent.postMessage({
                                        height: document.body.scrollHeight
                                    }, '*');
                                });
                            <\/script>
                        </body>
                        </html>
                    `;

                    iframeDoc.open();
                    iframeDoc.write(htmlContent);
                    iframeDoc.close();
                }, 100);
            });

            // Escuchar mensajes del iframe para ajustar la altura
            window.addEventListener('message', function(e) {
                if (e.data && e.data.height) {
                    const iframe = document.getElementById('previewFrame');
                    if (iframe) {
                        iframe.style.height = (e.data.height + 50) + 'px';
                    }
                }
            });

            let navbarIdToDelete = null;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));

            // Manejador para el botón de eliminar
            $('.delete-btn').on('click', function() {
                const button = $(this);
                navbarIdToDelete = button.data('navbar-id');
                const navbarName = button.data('navbar-name');

                // Actualizar el mensaje del modal con el nombre de la navbar si lo deseas
                $('#deleteConfirmModal .modal-body p:first').text(
                    `¿Estás seguro de que deseas eliminar la barra de navegación "${navbarName}"?`
                );

                deleteModal.show();
            });

            // Manejador para la confirmación de eliminación
            $('#confirmDelete').on('click', function() {
                if (!navbarIdToDelete) return;

                $.ajax({
                    url: `/navbars/${navbarIdToDelete}`,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        // Deshabilitar el botón de confirmar y mostrar spinner
                        const button = $(this);
                        button.prop('disabled', true);
                        button.html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Eliminando...'
                            );
                    },
                    success: function(response) {
                        if (response.success) {
                            // Cerrar el modal
                            deleteModal.hide();

                            // Mostrar mensaje de éxito
                            toastr.success('Barra de navegación eliminada correctamente');

                            // Eliminar la fila de la tabla con una animación
                            $(`tr:has(button[data-navbar-id="${navbarIdToDelete}"])`).fadeOut(
                                400,
                                function() {
                                    $(this).remove();

                                    // Si no quedan más filas, mostrar mensaje de "no hay barras"
                                    if ($('tbody tr').length === 0) {
                                        $('.table-responsive').replaceWith(
                                            '<div class="alert alert-info mx-4 text-center">' +
                                            '<i class="fas fa-info-circle me-2"></i>' +
                                            'No hay barras de navegación creadas.' +
                                            '</div>'
                                        );
                                    }
                                });
                        }
                    },
                    error: function(xhr) {
                        // Mostrar mensaje de error
                        toastr.error('Error al eliminar la barra de navegación');

                        // Si deseas mostrar un mensaje más específico basado en la respuesta del servidor
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            toastr.error(xhr.responseJSON.message);
                        }
                    },
                    complete: function() {
                        // Restablecer el botón de confirmar
                        const button = $('#confirmDelete');
                        button.prop('disabled', false);
                        button.html('Eliminar');

                        // Limpiar el ID a eliminar
                        navbarIdToDelete = null;
                    }
                });
            });

            // Limpiar el ID cuando se cierra el modal
            $('#deleteConfirmModal').on('hidden.bs.modal', function() {
                navbarIdToDelete = null;
            });
        });
    </script>
@endsection
