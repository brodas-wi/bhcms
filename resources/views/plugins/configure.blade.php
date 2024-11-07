@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Configurar Plugin: {{ $plugin->name }}</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-light text-white">
                        <h5 class="card-title mb-0">Estructura de Archivos</h5>
                    </div>
                    <div class="card-body">
                        <div id="file-tree"></div>
                        <div class="mt-3">
                            <button id="create-file-btn" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-plus"></i> Crear Archivo
                            </button>
                            <button id="create-folder-btn" class="btn btn-primary btn-sm">
                                <i class="fas fa-folder-plus"></i> Crear Carpeta
                            </button>
                            <button id="open-file-btn" class="btn btn-secondary btn-sm" style="display: none;">
                                <i class="fas fa-folder-open"></i> Abrir
                            </button>
                            <button id="delete-item-btn" class="btn btn-danger btn-sm" style="display: none;">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-light text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-code"></i> Editor</h5>
                    </div>
                    <div class="card-body">
                        <div id="file-editor" style="height: 400px;"></div>
                        <button id="save-file" class="btn btn-success mt-3">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear carpeta -->
    <div class="modal fade" id="createFolderModal" tabindex="-1" role="dialog" aria-labelledby="createFolderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFolderModalLabel">Crear Nueva Carpeta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createFolderForm">
                        <div class="form-group">
                            <label for="folderName">Nombre de la carpeta:</label>
                            <input type="text" class="form-control" id="folderName" required>
                        </div>
                        <div class="form-group">
                            <label for="folderParent">Carpeta padre:</label>
                            <input type="text" class="form-control" id="folderParent" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="createFolderBtn">Crear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear archivo -->
    <div class="modal fade" id="createFileModal" tabindex="-1" role="dialog" aria-labelledby="createFileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFileModalLabel">Crear Nuevo Archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createFileForm">
                        <div class="form-group">
                            <label for="fileName">Nombre del archivo:</label>
                            <input type="text" class="form-control" id="fileName" required>
                        </div>
                        <div class="form-group">
                            <label for="fileFolder">Carpeta de destino:</label>
                            <input type="text" class="form-control" id="fileFolder" readonly>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="createFileBtn">Crear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro que desea eliminar "<span id="itemToDelete"></span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" role="dialog"
        aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Aquí se inyectará el mensaje dinámico -->
                    <p id="notificationMessage"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/themes/default/style.min.css" rel="stylesheet">
    <style>
        #file-editor {
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .jstree-default .jstree-icon.jstree-themeicon-custom {
            background-size: contain;
        }

        .jstree-default .jstree-anchor {
            line-height: 24px;
            height: 24px;
        }

        .jstree-default .jstree-icon.folder-icon {
            color: #ffc107;
        }

        .jstree-default .jstree-icon.file-icon {
            color: #0154b8;
        }

        .custom-file-icon {
            font-size: 16px;
            vertical-align: middle;
            margin-right: 5px;
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jsTree -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.3.11/jstree.min.js"></script>

    <!-- Ace Editor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>

    <script>
        $(document).ready(function() {
            const pluginId = {{ $plugin->id }};
            let editor;
            let currentFile = null;
            let selectedNode = null;
            let currentPath = '';

            function updateButtons() {
                if (selectedNode) {
                    $('#delete-item-btn').show();
                    if (selectedNode.type !== 'folder') {
                        $('#open-file-btn').show();
                    } else {
                        $('#open-file-btn').hide();
                    }
                } else {
                    $('#delete-item-btn').hide();
                    $('#open-file-btn').hide();
                }
            }

            $('#create-file-btn').click(function() {
                updateCurrentPath();
                console.log('Abriendo modal de archivo. Ruta actual:', currentPath);
                $('#createFileModal').modal('show');
            });

            $('#createFileBtn').click(function() {
                const fileName = $('#fileName').val();
                const folderPath = $('#fileFolder').val();
                console.log('Creando archivo:', fileName, 'en la ruta:', folderPath);

                $.ajax({
                    url: `/plugins/${pluginId}/create-file`,
                    method: 'POST',
                    data: {
                        file_name: fileName,
                        folder_path: folderPath,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Respuesta del servidor (crear archivo):', response);
                        showNotification('Archivo creado correctamente', 'success');
                        refreshFileTree();
                        $('#createFileModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al crear archivo:', xhr.responseText);
                        showNotification('Error creando archivo: ' + xhr.responseText,
                            'danger');
                    }
                });
            });

            $('#create-folder-btn').click(function() {
                updateCurrentPath();
                console.log('Abriendo modal de carpeta. Ruta actual:', currentPath);
                $('#createFolderModal').modal('show');
            });

            $('#createFolderBtn').click(function() {
                const folderName = $('#folderName').val();
                const parentPath = $('#folderParent').val();
                const fullPath = parentPath ? `${parentPath}/${folderName}` : folderName;
                console.log('Creando carpeta:', folderName, 'en la ruta:', parentPath, 'Ruta completa:',
                    fullPath);

                $.ajax({
                    url: `/plugins/${pluginId}/create-folder`,
                    method: 'POST',
                    data: {
                        folder_path: fullPath,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Respuesta del servidor (crear carpeta):', response);
                        showNotification('Carpeta creada correctamente', 'success');
                        refreshFileTree();
                        $('#createFolderModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al crear carpeta:', xhr.responseText);
                        showNotification('Error creando carpeta: ' + xhr.responseText,
                            'danger');
                    }
                });
            });

            $('#delete-item-btn').click(function() {
                if (!selectedNode) {
                    showNotification('Por favor, seleccione un archivo o carpeta para eliminar', 'warning');
                    return;
                }

                $('#itemToDelete').text(selectedNode.text);
                $('#confirmDeleteModal').modal('show');
            });

            $('#confirmDeleteBtn').click(function() {
                const itemPath = selectedNode.id.replace(/^\/plugins\/[^\/]+/, '');
                const isFolder = selectedNode.type === 'folder';

                $.ajax({
                    url: `/plugins/${pluginId}/delete-item`,
                    method: 'POST',
                    data: {
                        item_path: itemPath,
                        is_folder: isFolder ? '1' : '0',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Respuesta del servidor (eliminar item):', response);
                        showNotification('Item eliminado correctamente', 'success');
                        refreshFileTree();
                        updateButtons();
                        clearSelection();
                        $('#confirmDeleteModal').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al eliminar item:', xhr.responseText);
                        let errorMessage = 'Error eliminando item';
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage += ': ' + errorResponse.message;
                            }
                        } catch (e) {
                            errorMessage += ': ' + xhr.responseText;
                        }
                        showNotification(errorMessage, 'danger');
                        $('#confirmDeleteModal').modal('hide');
                    }
                });
            });

            $('#open-file-btn').click(function() {
                if (!selectedNode || selectedNode.type === 'folder') {
                    showNotification('Por favor, seleccione un archivo para abrir', 'warning');
                    return;
                }

                filePath = selectedNode.id.replace(/^\/plugins\//, '');

                console.log('Normalized file path:', filePath);

                $.ajax({
                    url: `/plugins/${pluginId}/read-file`,
                    method: 'GET',
                    data: {
                        file_path: filePath
                    },
                    success: function(response) {
                        if (response.content !== undefined) {
                            editor.setValue(response.content);
                            editor.clearSelection();
                            editor.session.setMode(getAceMode(filePath));
                            showNotification('Archivo abierto correctamente', 'success');
                        } else {
                            showNotification('Error al abrir el archivo: Contenido no válido',
                                'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al abrir el archivo:', xhr.responseText);
                        showNotification('Error al abrir el archivo: ' + xhr.responseText,
                            'danger');
                    }
                });
            });

            function clearSelection() {
                selectedNode = null;
                $('#file-tree').jstree('deselect_all');
                updateButtons();
            }

            function refreshFileTree() {
                $('#file-tree').jstree(true).refresh();
            }

            function initializeFileTree() {
                $('#file-tree').jstree({
                    'core': {
                        'data': {
                            'url': `/plugins/${pluginId}/file-system`,
                            'dataType': 'json'
                        },
                        'themes': {
                            'responsive': false,
                            'variant': 'large'
                        }
                    },
                    'types': {
                        'default': {
                            'icon': 'fas fa-file file-icon custom-file-icon'
                        },
                        'folder': {
                            'icon': 'fas fa-folder folder-icon custom-file-icon'
                        }
                    },
                    'plugins': ['types']
                }).on('select_node.jstree', function(e, data) {
                    selectedNode = data.node;
                    currentPath = data.node.id.replace(/^\/plugins\/[^\/]+/, '');
                    if (selectedNode.type !== 'folder') {
                        loadFileContent(selectedNode.id);
                    }
                    updateButtons();
                }).on('deselect_node.jstree', function() {
                    selectedNode = null;
                    currentPath = null;
                    updateButtons();
                });
            }

            function updateCurrentPath() {
                $('#fileFolder').val(currentPath);
                $('#folderParent').val(currentPath);
                console.log('Ruta actualizada en los modales:', currentPath);
            }

            function updateDeleteButton() {
                if (selectedNode) {
                    $('#delete-item-btn').show();
                } else {
                    $('#delete-item-btn').hide();
                }
            }

            function initializeEditor() {
                editor = ace.edit("file-editor");
                editor.setTheme("ace/theme/monokai");
                editor.session.setMode("ace/mode/text");
                editor.setValue("// Selecciona un archivo para editar");
                editor.clearSelection();
                editor.setOptions({
                    fontSize: "14px",
                    showPrintMargin: false
                });
            }

            function getAceMode(filePath) {
                const extension = filePath.split('.').pop().toLowerCase();
                const modeMap = {
                    'js': 'javascript',
                    'php': 'php',
                    'html': 'html',
                    'css': 'css',
                    'json': 'json',
                    'md': 'markdown'
                    // Añade más extensiones según sea necesario
                };
                return `ace/mode/${modeMap[extension] || 'text'}`;
            }

            function loadFileContent(filePath) {
                console.log('Original file path:', filePath);

                // Eliminar el prefijo '/plugins/' si existe
                filePath = filePath.replace(/^\/plugins\//, '');

                console.log('Normalized file path:', filePath);

                $.ajax({
                    url: `/plugins/${pluginId}/read-file`,
                    method: 'GET',
                    data: {
                        file_path: filePath
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log('Server response:', response);
                        if (response.content !== undefined) {
                            editor.setValue(response.content);
                            editor.clearSelection();
                            currentFile = filePath;
                            console.log('File loaded successfully. Current file:', currentFile);
                        } else {
                            console.error('Unexpected response format:', response);
                            showNotification('Error: Formato de respuesta inesperado del servidor.',
                                'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading file:', status, error);
                        showNotification('Error al cargar el archivo: ' + error, 'danger');
                    }
                });
            }

            function setupSaveButton() {
                $('#save-file').click(function() {
                    if (!currentFile) {
                        showNotification('Por favor selecciona un archivo para ser guardado', 'warning');
                        return;
                    }

                    const content = editor.getValue();

                    $.ajax({
                        url: `/plugins/${pluginId}/write-file`,
                        method: 'POST',
                        data: {
                            file_path: currentFile,
                            content: content,
                            _token: '{{ csrf_token() }}'
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('File saved:', response);
                            showNotification('Archivo guardado correctamente', 'success');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error saving file:', status, error);
                            console.log('Server response:', xhr.responseText);
                            showNotification('Error guardando archivo: ' + xhr.responseText,
                                'danger');
                        }
                    });
                });
            }

            function showNotification(message, type) {
                // Cambiar el color del encabezado según el tipo de notificación
                let modalHeader = $('#notificationModal .modal-header');
                modalHeader.removeClass('bg-success bg-danger bg-warning');

                switch (type) {
                    case 'success':
                        modalHeader.addClass('bg-success text-white');
                        break;
                    case 'danger':
                        modalHeader.addClass('bg-danger text-white');
                        break;
                    case 'warning':
                        modalHeader.addClass('bg-warning text-dark');
                        break;
                    default:
                        modalHeader.addClass('bg-info text-white');
                        break;
                }

                // Insertar el mensaje en el modal
                $('#notificationMessage').text(message);

                // Mostrar el modal
                $('#notificationModal').modal('show');
            }


            initializeFileTree();
            initializeEditor();
            updateButtons();
            updateCurrentPath();
            setupSaveButton();

            console.log('Inicialización completa. Ruta inicial:', currentPath);

            window.debugFileTree = function() {
                console.log('File tree instance:', $('#file-tree').jstree(true));
                console.log('File tree data:', $('#file-tree').jstree(true).get_json('#', {
                    flat: true
                }));
            }
        });
    </script>
@endpush
