<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor de Artículos</title>

    <!-- Dependencies -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.20.3/css/grapes.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css">

    <style>
        :root {
            --editor-height: 100vh;
            --panel-width: 280px;
            --top-bar-height: 60px;
        }

        body {
            margin: 0;
            overflow: hidden;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
        }

        .editor-container {
            height: var(--editor-height);
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            height: var(--top-bar-height);
            background: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            color: white;
        }

        .editor-actions {
            display: flex;
            gap: 10px;
        }

        .main-content {
            display: flex;
            height: calc(var(--editor-height) - var(--top-bar-height));
        }

        .panel {
            width: var(--panel-width);
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
            overflow-y: auto;
            transition: transform 0.3s;
        }

        .panel.right {
            border-right: none;
            border-left: 1px solid #dee2e6;
        }

        .editor-canvas {
            flex: 1;
            overflow: hidden;
        }

        .device-switcher {
            display: flex;
            gap: 10px;
            margin: 0 20px;
        }

        .btn-editor {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-editor:hover {
            background: rgba(255,255,255,0.1);
        }

        .btn-editor.active {
            background: rgba(255,255,255,0.2);
        }

        .btn-save {
            background: #27ae60;
            border: none;
        }

        .btn-save:hover {
            background: #219a52;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(-20px);
            transition: transform 0.3s;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .panel {
                position: fixed;
                top: var(--top-bar-height);
                bottom: 0;
                z-index: 100;
                transform: translateX(-100%);
            }

            .panel.right {
                transform: translateX(100%);
            }

            .panel.active {
                transform: translateX(0);
            }

            .device-switcher {
                display: none;
            }
        }

        .placeholder-img {
            background-color: #e9ecef;
            border: 2px dashed #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 200px;
            color: #6c757d;
        }

        .placeholder-img::before {
            content: 'Haga clic o arrastre una imagen aquí';
            font-size: 14px;
        }

        /* Notification styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background: white;
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateX(120%);
            transition: transform 0.3s;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            border-left: 4px solid #27ae60;
        }

        .notification.error {
            border-left: 4px solid #e74c3c;
        }
    </style>
</head>
<body>
    <div class="editor-container">
        <div class="top-bar">
            <div class="editor-actions">
                <button class="btn-editor" id="toggle-blocks">
                    <i class="fas fa-th-large"></i> Bloques
                </button>
                <button class="btn-editor" id="toggle-styles">
                    <i class="fas fa-paint-brush"></i> Estilos
                </button>
            </div>

            <div class="device-switcher">
                <button class="btn-editor active" data-device="desktop">
                    <i class="fas fa-desktop"></i>
                </button>
                <button class="btn-editor" data-device="tablet">
                    <i class="fas fa-tablet-alt"></i>
                </button>
                <button class="btn-editor" data-device="mobile">
                    <i class="fas fa-mobile-alt"></i>
                </button>
            </div>

            <div class="editor-actions">
                <button class="btn-editor" id="preview-btn">
                    <i class="fas fa-eye"></i> Vista previa
                </button>
                <button class="btn-editor btn-save" id="save-btn">
                    <i class="fas fa-save"></i> Guardar
                </button>
            </div>
        </div>

        <div class="main-content">
            <div class="panel" id="blocks-panel">
                <div id="blocks"></div>
            </div>
            <div class="editor-canvas">
                <div id="gjs"></div>
            </div>
            <div class="panel right" id="styles-panel">
                <div id="styles-container"></div>
                <div id="traits-container"></div>
                <div id="layers-container"></div>
            </div>
        </div>
    </div>

    <!-- Save Modal -->
    <div class="modal-overlay" id="save-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Guardar Artículo</h5>
                <button class="btn-close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="article-form">
                    <div class="form-grid">
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tipo</label>
                            <select class="form-control" name="type" required>
                                <option value="">Seleccionar...</option>
                                <option value="article">Artículo</option>
                                <option value="blog">Blog</option>
                                <option value="news">Noticia</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-control select2" name="category" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">Tecnología</option>
                                <option value="2">Negocios</option>
                                <option value="3">Cultura</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Etiquetas</label>
                            <select class="form-control select2" name="tags" multiple>
                                <option value="1">Web</option>
                                <option value="2">Diseño</option>
                                <option value="3">Programación</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select class="form-control" name="status">
                                <option value="draft">Borrador</option>
                                <option value="published">Publicar</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" id="submit-article">Guardar</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/grapesjs/0.20.3/grapes.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bloques personalizados
            const customBlocks = [
                {
                    id: 'section',
                    label: 'Sección',
                    category: 'Layout',
                    content: `
                        <section class="py-5">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>Añade tu contenido aquí...</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    `
                },
                {
                    id: 'container',
                    label: 'Contenedor',
                    category: 'Layout',
                    content: '<div class="container my-3"></div>'
                },
                {
                    id: 'row',
                    label: 'Fila',
                    category: 'Layout',
                    content: '<div class="row"></div>'
                },
                {
                    id: 'column',
                    label: 'Columna',
                    category: 'Layout',
                    content: '<div class="col"></div>'
                },
                {
                    id: 'two-columns',
                    label: '2 Columnas',
                    category: 'Layout',
                    content: `
                        <div class="row">
                            <div class="col-md-6">Columna 1</div>
                            <div class="col-md-6">Columna 2</div>
                        </div>
                    `
                },
                {
                    id: 'three-columns',
                    label: '3 Columnas',
                    category: 'Layout',
                    content: `
                        <div class="row">
                            <div class="col-md-4">Columna 1</div>
                            <div class="col-md-4">Columna 2</div>
                            <div class="col-md-4">Columna 3</div>
                        </div>
                    `
                },

                // Bloques de Contenido
                {
                    id: 'heading',
                    label: 'Encabezado',
                    category: 'Contenido',
                    content: `
                        <div class="mb-4">
                            <h2 class="display-4">Título Principal</h2>
                            <p class="lead">Subtítulo o descripción complementaria</p>
                        </div>
                    `
                },
                {
                    id: 'text-block',
                    label: 'Bloque de Texto',
                    category: 'Contenido',
                    content: `
                        <div class="py-3">
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    `
                },
                {
                    id: 'image-text',
                    label: 'Imagen + Texto',
                    category: 'Contenido',
                    content: `
                        <div class="row align-items-center my-5">
                            <div class="col-md-6">
                                <div class="img-placeholder bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <span class="text-muted">Arrastra una imagen aquí</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h3>Título de la sección</h3>
                                <p class="lead">Subtítulo o introducción breve</p>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                                <button class="btn btn-primary">Llamada a la acción</button>
                            </div>
                        </div>
                    `
                },
                {
                    id: 'video-responsive',
                    label: 'Video Responsive',
                    category: 'Contenido',
                    content: `
                        <div class="ratio ratio-16x9 my-4">
                            <iframe src="https://www.youtube.com/embed/your-video-id" title="Video" allowfullscreen></iframe>
                        </div>
                    `
                },

                // Bloques de Componentes
                {
                    id: 'card',
                    label: 'Tarjeta',
                    category: 'Componentes',
                    content: `
                        <div class="card my-3">
                            <div class="img-placeholder bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <span class="text-muted">Imagen de la tarjeta</span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Título de la tarjeta</h5>
                                <p class="card-text">Contenido de la tarjeta. Agrega aquí una descripción breve.</p>
                                <a href="#" class="btn btn-primary">Botón</a>
                            </div>
                        </div>
                    `
                },
                {
                    id: 'alert',
                    label: 'Alerta',
                    category: 'Componentes',
                    content: `
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            <strong>¡Importante!</strong> Mensaje de alerta para el usuario.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `
                },
                {
                    id: 'buttons-group',
                    label: 'Grupo de Botones',
                    category: 'Componentes',
                    content: `
                        <div class="btn-group my-3" role="group" aria-label="Grupo de botones">
                            <button type="button" class="btn btn-primary">Izquierda</button>
                            <button type="button" class="btn btn-primary">Centro</button>
                            <button type="button" class="btn btn-primary">Derecha</button>
                        </div>
                    `
                },

                // Bloques de Formulario
                {
                    id: 'contact-form',
                    label: 'Formulario de Contacto',
                    category: 'Formularios',
                    content: `
                        <form class="my-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" placeholder="Tu nombre">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" placeholder="tu@email.com">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Mensaje</label>
                                <textarea class="form-control" id="message" rows="3" placeholder="Tu mensaje"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    `
                },

                // Bloques de Características
                {
                    id: 'features',
                    label: 'Características',
                    category: 'Secciones',
                    content: `
                        <section class="py-5">
                            <div class="container">
                                <div class="row g-4">
                                    <div class="col-md-4 text-center">
                                        <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-3 p-3">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <h3>Característica 1</h3>
                                        <p>Descripción breve de la característica.</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-3 p-3">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                        <h3>Característica 2</h3>
                                        <p>Descripción breve de la característica.</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="feature-icon bg-primary bg-gradient text-white rounded-3 mb-3 p-3">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <h3>Característica 3</h3>
                                        <p>Descripción breve de la característica.</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    `
                },

                // Bloques de Llamada a la acción
                {
                    id: 'cta-section',
                    label: 'Llamada a la Acción',
                    category: 'Secciones',
                    content: `
                        <section class="py-5 bg-primary text-white">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-8 text-center text-lg-start">
                                        <h2 class="display-4 fw-bold">¡Actúa ahora!</h2>
                                        <p class="lead">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                    </div>
                                    <div class="col-lg-4 text-center text-lg-end">
                                        <button class="btn btn-light btn-lg px-4">¡Comenzar!</button>
                                    </div>
                                </div>
                            </div>
                        </section>
                    `
                }
            ];

            // Initialize GrapesJS Editor
            const editor = grapesjs.init({
                container: '#gjs',
                height: '100%',
                width: 'auto',
                storageManager: false,
                blockManager: {
                    appendTo: '#blocks',
                    blocks: customBlocks
                },
                styleManager: {
                    appendTo: '#styles-container',
                    sectors: [
                        {
                            name: 'Dimensiones',
                            open: false,
                            properties: ['width', 'height', 'min-width', 'min-height', 'margin', 'padding']
                        },
                        {
                            name: 'Tipografía',
                            open: false,
                            properties: [
                                'font-family',
                                'font-size',
                                'font-weight',
                                'letter-spacing',
                                'color',
                                'line-height',
                                'text-align',
                                'text-decoration',
                                'text-shadow'
                            ]
                        },
                        {
                            name: 'Decoración',
                            open: false,
                            properties: [
                                'background-color',
                                'border',
                                'border-radius',
                                'box-shadow'
                            ]
                        },
                        {
                            name: 'Extra',
                            open: false,
                            properties: ['opacity', 'cursor', 'position', 'overflow']
                        }
                    ]
                },
                layerManager: {
                    appendTo: '#layers-container'
                },
                traitManager: {
                    appendTo: '#traits-container'
                },
                panels: {
                    defaults: []
                },
                deviceManager: {
                    devices: [
                        {
                            name: 'Desktop',
                            width: '', // Full width
                        },
                        {
                            name: 'Tablet',
                            width: '768px',
                            widthMedia: '992px',
                        },
                        {
                            name: 'Mobile',
                            width: '320px',
                            widthMedia: '480px',
                        }
                    ]
                },
                assetManager: {
                    upload: '/upload-image',
                    uploadName: 'file',
                    assets: [],
                    autoAdd: true,
                    uploadFile: function(e) {
                        const files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                        const formData = new FormData();

                        for(let i = 0; i < files.length; i++) {
                            formData.append('file', files[i]);
                        }

                        return fetch('/upload-image', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                return { data: { url: data.url } };
                            }
                            throw new Error(data.message || 'Error al subir la imagen');
                        });
                    }
                },
                canvas: {
                    styles: [
                        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css',
                        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css'
                    ],
                    scripts: [
                        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js'
                    ]
                }
            });

            editor.Panels.addPanel({
                id: 'panel-top',
                el: '.panel__top',
            });

            editor.Panels.addPanel({
                id: 'basic-actions',
                el: '.panel__basic-actions',
                buttons: [
                    {
                        id: 'show-blocks',
                        active: true,
                        className: 'btn-toggle-blocks',
                        label: '<i class="fas fa-th-large"></i>',
                        command: 'show-blocks',
                        togglable: true,
                    },
                    {
                        id: 'show-styles',
                        active: false,
                        className: 'btn-toggle-styles',
                        label: '<i class="fas fa-paint-brush"></i>',
                        command: 'show-styles',
                        togglable: true,
                    }
                ]
            });

            // Cargar imágenes existentes al iniciar
            fetch('/get-images')
                .then(response => response.json())
                .then(data => {
                    if (data.images) {
                        editor.AssetManager.add(data.images);
                    }
                })
                .catch(error => console.error('Error loading images:', error));

            // Añadir bloques al editor
            customBlocks.forEach(block => {
                editor.BlockManager.add(block.id, {
                    label: block.label,
                    category: block.category,
                    content: block.content,
                    render: ({ model, className }) => `
                        <div class="${className}">
                            <i class="fa fa-cubes"></i>
                            <div class="my-label-block">${block.label}</div>
                        </div>
                    `
                });
            });

            // Panel toggle handlers
            document.getElementById('toggle-blocks').addEventListener('click', function() {
                document.getElementById('blocks-panel').classList.toggle('active');
                this.classList.toggle('active');
            });

            document.getElementById('toggle-styles').addEventListener('click', function() {
                document.getElementById('styles-panel').classList.toggle('active');
                this.classList.toggle('active');
            });

            // Device switcher
            document.querySelectorAll('[data-device]').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('[data-device]').forEach(btn =>
                        btn.classList.remove('active')
                    );
                    this.classList.add('active');
                    editor.setDevice(this.dataset.device);
                });
            });

            // Save functionality
            document.getElementById('save-btn').addEventListener('click', function() {
                const modal = document.getElementById('save-modal');
                modal.classList.add('active');
            });

            // Close modal
            document.querySelectorAll('[data-dismiss="modal"]').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('save-modal').classList.remove('active');
                });
            });

            // Form submission
            document.getElementById('submit-article').addEventListener('click', function() {
                const form = document.getElementById('article-form');
                const formData = new FormData(form);

                // Añadir contenido del editor
                formData.append('content', editor.getHtml());
                formData.append('styles', editor.getCss());

                showNotification('Guardando contenido...', 'info');

                fetch('/contents', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    showNotification('Contenido guardado exitosamente', 'success');
                    document.getElementById('save-modal').classList.remove('active');

                    // Limpiar borrador local
                    localStorage.removeItem('content-draft');

                    // Redireccionar
                    setTimeout(() => {
                        window.location.href = data.redirect || '/contents';
                    }, 1500);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Error al guardar: ' + error.message, 'error');
                });
            });

            // Notification system
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;

                const icon = type === 'success' ? 'check-circle' :
                            type === 'error' ? 'exclamation-circle' :
                            'info-circle';

                notification.innerHTML = `
                    <i class="fas fa-${icon}"></i>
                    <span>${message}</span>
                `;

                document.body.appendChild(notification);

                requestAnimationFrame(() => {
                    notification.classList.add('show');
                });

                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Autoguardado
            let autoSaveTimeout;
            editor.on('change', () => {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    const content = {
                        html: editor.getHtml(),
                        css: editor.getCss(),
                        timestamp: new Date().toISOString()
                    };
                    localStorage.setItem('content-draft', JSON.stringify(content));
                    showNotification('Borrador guardado automáticamente', 'info');
                }, 30000); // Autoguardar cada 30 segundos de inactividad
            });

            // Cargar borrador si existe
            const draft = localStorage.getItem('content-draft');
            if (draft) {
                const { html, css, timestamp } = JSON.parse(draft);
                const draftDate = new Date(timestamp);
                const now = new Date();
                const hoursSinceDraft = (now - draftDate) / (1000 * 60 * 60);

                if (hoursSinceDraft < 24) {
                    const shouldRestore = confirm(
                        `Se encontró un borrador guardado de hace ${Math.round(hoursSinceDraft)} horas. ¿Desea restaurarlo?`
                    );
                    if (shouldRestore) {
                        editor.setComponents(html);
                        editor.setStyle(css);
                    } else {
                        localStorage.removeItem('content-draft');
                    }
                } else {
                    localStorage.removeItem('content-draft');
                }
            }

            // Prevenir pérdida de cambios
            window.addEventListener('beforeunload', (event) => {
                if (editor.getDirtyCount() > 0) {
                    event.preventDefault();
                    event.returnValue = '¿Estás seguro de que quieres salir? Los cambios no guardados se perderán.';
                }
            });

            // Atajos de teclado
            document.addEventListener('keydown', (event) => {
                if (event.ctrlKey || event.metaKey) {
                    switch (event.key.toLowerCase()) {
                        case 's':
                            event.preventDefault();
                            document.getElementById('save-btn').click();
                            break;
                    }
                }
            });
        });
    </script>
</body>
</html>
