<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo e(isset($page->name) ? 'Editar Página: ' . $page->name : 'Crear Nueva Página'); ?></title>
        <link rel="stylesheet" href="https://unpkg.com/grapesjs@0.20.3/dist/css/grapes.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css">
        <style>
            body,
            html {
                margin: 0;
                padding: 0;
                height: 100%;
                overflow: hidden;
            }

            #gjs {
                border: none;
                width: 100%;
                height: calc(100vh - 50px);
            }

            .panel__top {
                padding: 5px;
                width: 100%;
                display: flex;
                position: initial;
                justify-content: space-between;
                background-color: #444;
            }

            .panel__basic-actions,
            .panel__devices,
            .panel__switcher {
                position: initial;
            }

            .editor-row {
                display: flex;
                justify-content: flex-start;
                align-items: stretch;
                flex-wrap: nowrap;
                height: calc(100vh - 50px);
            }

            .editor-canvas {
                flex-grow: 1;
                height: 100%;
                overflow: hidden;
            }

            .panel__right {
                flex-basis: 230px;
                position: relative;
                overflow-y: auto;
            }

            #blocks {
                height: 100%;
                overflow-y: auto;
            }

            .gjs-frame {
                width: 100% !important;
                height: 100% !important;
            }

            .gjs-cv-canvas {
                width: 100% !important;
                height: 100% !important;
                top: 0 !important;
                left: 0 !important;
            }

            .editor-row {
                display: flex;
                justify-content: flex-start;
                align-items: stretch;
                flex-wrap: nowrap;
                height: calc(100vh - 50px);
            }

            .editor-canvas {
                flex-grow: 1;
                height: 100%;
                overflow: hidden;
            }

            @media (max-width: 768px) {
                .editor-row {
                    flex-direction: column;
                }

                .editor-canvas {
                    height: calc(100vh - 250px);
                }
            }

            .CodeMirror {
                height: 400px;
                border: 1px solid #ddd;
            }

            .editor-container {
                display: flex;
                flex-direction: column;
                height: 100%;
            }

            .editor-container h3 {
                margin-top: 10px;
                margin-bottom: 5px;
            }

            /* Plugin-specific block styles */
            [data-gjs-type].plugin-block,
            div[class*="gjs-block"][data-plugin-block] {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 12px;
                border-radius: 4px;
                transition: all 0.2s ease;
                cursor: move;
                background: white;
            }

            /* Target only plugin icons */
            [data-gjs-type].plugin-block i,
            div[data-plugin-block] i {
                font-size: 24px;
                margin-bottom: 8px;
                color: #666;
            }

            /* Plugin block hover effects */
            [data-gjs-type].plugin-block:hover,
            div[data-plugin-block]:hover {
                background-color: #f5f5f5;
                transform: translateY(-1px);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            /* Plugin block title */
            [data-gjs-type].plugin-block div,
            div[data-plugin-block] .plugin-title {
                font-size: 14px;
                font-weight: 500;
                text-align: center;
                color: #333;
            }

            /* Estilos adicionales para mejorar la apariencia general */
            .panel__basic-actions button,
            .panel__devices button,
            .panel__switcher button {
                background-color: #555;
                color: white;
                border: none;
                padding: 5px 10px;
                margin: 0 5px;
                border-radius: 3px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .panel__basic-actions button:hover,
            .panel__devices button:hover,
            .panel__switcher button:hover {
                background-color: #666;
            }
        </style>
    </head>

    <body>
        <div class="panel__top">
            <div class="panel__basic-actions"></div>
            <div class="panel__devices"></div>
            <div class="panel__switcher"></div>
        </div>
        <div class="editor-row">
            <div class="panel__left" style="width: 200px;">
                <div id="blocks"></div>
            </div>
            <div class="editor-canvas">
                <div id="gjs"></div>
            </div>
            <div class="panel__right">
                <div id="layers-container"></div>
                <div id="styles-container"></div>
                <div id="traits-container"></div>
            </div>
        </div>
        <div class="code-editor">
            <div class="code-editor-content">
                <div class="editor-section">
                    <h3>HTML</h3>
                    <textarea id="html-editor"></textarea>
                </div>
                <div class="editor-section">
                    <h3>CSS</h3>
                    <textarea id="css-editor"></textarea>
                </div>
            </div>
            <div class="code-editor-buttons">
                <button id="apply-code">Aplicar Cambios</button>
                <button id="close-editor">Cerrar Editor</button>
            </div>
        </div>

        <!-- Exit without save modal -->
        <div class="modal fade" id="exitConfirmModal" tabindex="-1" aria-labelledby="exitConfirmModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exitConfirmModalLabel">Confirmar salida</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro de que quieres salir? Los cambios no guardados se perderán.</p>
                        <div class="form-group">
                            <label for="exit-version-type">Tipo de versión:</label>
                            <select class="form-control" id="exit-version-type">
                                <option value="none">No guardar</option>
                                <option value="current">Versión actual (<?php echo e($page->version ?? '1.0.0'); ?>)</option>
                                <option value="minor">Versión menor
                                    (<?php echo e($page->version ? explode('.', $page->version)[0] . '.' . (explode('.', $page->version)[1] + 1) . '.0' : '1.1.0'); ?>)
                                </option>
                                <option value="major">Versión mayor
                                    (<?php echo e($page->version ? explode('.', $page->version)[0] + 1 . '.0.0' : '2.0.0'); ?>)
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmExit">Salir</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Añade este modal al final de tu archivo HTML, justo antes de cerrar el body -->
        <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="saveModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saveModalLabel">Guardar cambios</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="save-version-type">Tipo de versión:</label>
                            <select class="form-control" id="save-version-type">
                                <option value="current">Versión actual (<?php echo e($page->version ?? '1.0.0'); ?>)</option>
                                <option value="minor">Versión menor
                                    (<?php echo e($page->version ? explode('.', $page->version)[0] . '.' . (explode('.', $page->version)[1] + 1) . '.0' : '1.1.0'); ?>)
                                </option>
                                <option value="major">Versión mayor
                                    (<?php echo e($page->version ? explode('.', $page->version)[0] + 1 . '.0.0' : '2.0.0'); ?>)
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirm-save">Guardar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Successful modal -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="successModalLabel">Éxito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Página guardada correctamente.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="successModalOk">OK</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Modal -->
        <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="errorModalBody">
                        Ha ocurrido un error al guardar la página.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://unpkg.com/grapesjs@0.20.3"></script>
        <script src="https://unpkg.com/grapesjs-blocks-basic@1.0.1"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify-html.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify-css.min.js"></script>
        <script>
            var availablePlugins = <?php echo json_encode($availablePlugins, 15, 512) ?>;
            var serializedContent = <?php echo json_encode($serializedContent, 15, 512) ?>;
            var activePlugins = <?php echo json_encode($activePlugins, 15, 512) ?>;
        </script>
        <script>
            const handlePluginResponse = async (response) => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return await response.json();
                } else {
                    // For non-JSON responses, wrap the HTML in a success object
                    const html = await response.text();
                    return {
                        success: response.ok,
                        html: html,
                        error: response.ok ? null : 'Invalid response format'
                    };
                }
            };

            const initializePluginSystem = (editor) => {
                // Register dynamic component types for each plugin
                availablePlugins.forEach(plugin => {
                    editor.Components.addType(plugin.name, {
                        model: {
                            defaults: {
                                tagName: 'div',
                                droppable: true,
                                attributes: {
                                    'data-gjs-type': plugin.name
                                },
                                components: [],
                                traits: [{
                                    type: 'select',
                                    label: 'Vista',
                                    name: 'view',
                                    options: plugin.views.map(view => ({
                                        value: view,
                                        name: view.charAt(0).toUpperCase() + view.slice(
                                            1)
                                    }))
                                }]
                            }
                        },
                        view: {
                            async onRender() {
                                try {
                                    const viewName = this.model.get('traits')
                                        .where({
                                            name: 'view'
                                        })[0]?.get('value') || 'index';

                                    const response = await fetch(
                                        `/plugins/${plugin.id}/preview/${viewName}`);
                                    const data = await handlePluginResponse(response);

                                    if (data.success) {
                                        this.el.innerHTML = data.html;

                                        // Initialize plugin if needed
                                        if (typeof window[`initialize${plugin.name}`] === 'function') {
                                            window[`initialize${plugin.name}`](this.el);
                                        }
                                    } else {
                                        throw new Error(data.error || 'Error loading plugin content');
                                    }
                                } catch (error) {
                                    console.error(`Error rendering plugin ${plugin.name}:`, error);
                                    this.el.innerHTML = `
                                    <div class="plugin-error">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <p>Error loading ${plugin.original_name}</p>
                                        <small>${error.message}</small>
                                    </div>`;
                                }
                            }
                        }
                    });

                    // Add plugin block
                    editor.BlockManager.add(plugin.name, {
                        label: `
                            <div class="plugin-block">
                                <i class="fas fa-${plugin.icon || 'puzzle-piece'}"></i>
                                <div>${plugin.original_name}</div>
                            </div>
                        `,
                        content: {
                            type: plugin.name
                        },
                        category: 'Plugins'
                    });
                });

                // Handle drag & drop errors
                editor.on('block:drag:stop', component => {
                    const type = component.get('type');
                    const plugin = availablePlugins.find(p => p.name === type);

                    if (plugin) {
                        component.getView().updateContent();
                    }
                });

                // Add component selected handling
                editor.on('component:selected', component => {
                    const type = component.get('type');
                    const plugin = availablePlugins.find(p => p.name === type);

                    if (plugin) {
                        // Update traits panel
                        const view = component.getView();
                        if (view) {
                            view.updateContent();
                        }
                    }
                });

                // Add error styles
                const style = document.createElement('style');
                style.textContent = `
                    .plugin-error {
                        padding: 20px;
                        text-align: center;
                        color: #721c24;
                        background-color: #f8d7da;
                        border: 1px solid #f5c6cb;
                        border-radius: 4px;
                    }
                    .plugin-error i {
                        font-size: 24px;
                        margin-bottom: 10px;
                        color: #dc3545;
                    }
                    .plugin-error p {
                        margin: 5px 0;
                        font-weight: bold;
                    }
                    .plugin-error small {
                        color: #6c757d;
                    }
                `;
                document.head.appendChild(style);
            };

            document.addEventListener('DOMContentLoaded', function() {
                const editor = grapesjs.init({
                    container: '#gjs',
                    height: '100%',
                    width: 'auto',
                    storageManager: false,
                    plugins: ['gjs-blocks-basic'],
                    pluginsOpts: {
                        'gjs-blocks-basic': {
                            blocks: ['column1', 'column2', 'column3', 'column3-7', 'text', 'link', 'image',
                                'video',
                                'map'
                            ],
                            flexGrid: 1,
                            stylePrefix: 'gjs-',
                            labelColumn1: 'Columna 1',
                            labelColumn2: 'Columna 2',
                            labelColumn3: 'Columna 3',
                            labelColumn37: 'Columna 3/7',
                            labelText: 'Texto',
                            labelLink: 'Enlace',
                            labelImage: 'Imagen',
                            labelVideo: 'Video',
                            labelMap: 'Mapa',
                            category: 'Básico'
                        },
                    },
                    canvas: {
                        styles: ['https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'],
                        scripts: [
                            'https://code.jquery.com/jquery-3.5.1.slim.min.js',
                            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
                        ]
                    },
                    blockManager: {
                        appendTo: '#blocks',
                        blocks: [...availablePlugins.map(plugin => ({
                            id: plugin.name,
                            label: `
                                <div class="plugin-block">
                                    <i class="fas fa-${plugin.icon}"></i>
                                    <div>${plugin.original_name}</div>
                                </div>
                            `,
                            category: 'Plugins',
                            content: {
                                type: plugin.name
                            }
                        }))]
                    },
                    layerManager: {
                        appendTo: '#layers-container'
                    },
                    styleManager: {
                        appendTo: '#styles-container'
                    },
                    traitManager: {
                        appendTo: '#traits-container'
                    },
                    deviceManager: {
                        devices: [{
                                name: 'Escritorio',
                                width: ''
                            },
                            {
                                name: 'Tablet',
                                width: '768px'
                            },
                            {
                                name: 'Móvil',
                                width: '320px'
                            }
                        ]
                    },
                    panels: {
                        defaults: [{
                                id: 'panel-devices',
                                el: '.panel__devices',
                                buttons: [{
                                        id: 'device-desktop',
                                        label: '<i class="fa fa-desktop"></i>',
                                        command: 'set-device-desktop',
                                        active: true,
                                        togglable: false,
                                        attributes: {
                                            title: 'Vista Escritorio'
                                        }
                                    },
                                    {
                                        id: 'device-tablet',
                                        label: '<i class="fa fa-tablet"></i>',
                                        command: 'set-device-tablet',
                                        togglable: false,
                                        attributes: {
                                            title: 'Vista Tablet'
                                        }
                                    },
                                    {
                                        id: 'device-mobile',
                                        label: '<i class="fa fa-mobile"></i>',
                                        command: 'set-device-mobile',
                                        togglable: false,
                                        attributes: {
                                            title: 'Vista Movil'
                                        }
                                    }
                                ],
                            },
                            {
                                id: 'panel-switcher',
                                el: '.panel__switcher',
                                buttons: [{
                                        id: 'show-layers',
                                        label: 'Capas',
                                        command: 'show-layers',
                                        active: true
                                    },
                                    {
                                        id: 'show-style',
                                        label: 'Estilos',
                                        command: 'show-styles'
                                    },
                                    {
                                        id: 'show-traits',
                                        label: 'Atributos',
                                        command: 'show-traits'
                                    }
                                ],
                            },
                            {
                                id: 'panel-basic-actions',
                                el: '.panel__basic-actions',
                                buttons: [{
                                        id: 'back-to-index',
                                        className: 'btn-back-to-index',
                                        label: '<i class="fa fa-arrow-left"></i>',
                                        command: 'back-to-index',
                                        attributes: {
                                            title: 'Regresar al índice de plantillas'
                                        }
                                    }, {
                                        id: 'visibility',
                                        active: true,
                                        className: 'btn-toggle-borders',
                                        label: '<i class="fa fa-clone"></i>',
                                        command: 'sw-visibility',
                                        attributes: {
                                            title: 'Ver/Ocultar componentes'
                                        }
                                    },
                                    {
                                        id: 'edit-code',
                                        className: 'btn-edit-code',
                                        label: '<i class="fa fa-code"></i>',
                                        command: 'edit-code',
                                        attributes: {
                                            title: 'Editar código'
                                        }
                                    },
                                    {
                                        id: 'fullscreen',
                                        className: 'btn-toggle-fullscreen',
                                        label: '<i class="fa fa-expand"></i>',
                                        command: 'fullscreen',
                                        attributes: {
                                            title: 'Pantalla completa'
                                        }
                                    },
                                    {
                                        id: 'save',
                                        className: 'btn-save',
                                        label: '<i class="fa fa-floppy-o"></i>',
                                        command: 'save-page',
                                        attributes: {
                                            title: 'Guardar'
                                        }
                                    }
                                ],
                            }
                        ]
                    },
                    i18n: {
                        locale: 'es',
                        messages: {
                            es: {
                                styleManager: {
                                    empty: 'Seleccione un elemento para usar el Administrador de estilos',
                                    layer: 'Capa',
                                    fileButton: 'Imágenes',
                                    sectors: {
                                        general: 'General',
                                        layout: 'Diseño',
                                        typography: 'Tipografía',
                                        decorations: 'Decoraciones',
                                        extra: 'Extra',
                                        flex: 'Flex',
                                        dimension: 'Dimensión'
                                    }
                                },
                                traitManager: {
                                    empty: 'Seleccione un elemento para usar el Administrador de rasgos',
                                    label: 'Ajustes de componentes',
                                    traits: {
                                    }
                                },
                                blockManager: {
                                    category: 'Categorías',
                                    categories: {
                                        basic: 'Básicos',
                                        layout: 'Diseño',
                                        'basic-blocks': 'Bloques Básicos',
                                    },
                                    labels: {
                                        'basic-blocks': 'Bloques Básicos',
                                        column1: 'Columna 1',
                                        column2: 'Columna 2',
                                        column3: 'Columna 3',
                                        'column3-7': 'Columna 3/7',
                                        text: 'Texto',
                                        link: 'Enlace',
                                        image: 'Imagen',
                                        video: 'Video',
                                        map: 'Mapa'
                                    }
                                }
                            }
                        }
                    }
                });

                initializePluginSystem(editor);

                // Add function to back button, return to pages index
                // Añade esto a tu script JavaScript existente
                editor.Commands.add('back-to-index', {
                    run: function(editor, sender) {
                        var exitModal = new bootstrap.Modal(document.getElementById('exitConfirmModal'));
                        exitModal.show();
                        document.getElementById('confirmExit').onclick = function() {
                            const versionType = document.getElementById('exit-version-type').value;
                            if (versionType !== 'none') {
                                // Si se seleccionó una versión, guarda los cambios antes de salir
                                editor.runCommand('save-page');
                            } else {
                                // Si no se seleccionó una versión, simplemente sal
                                window.location.href = '<?php echo e(route('pages.index')); ?>';
                            }
                        };
                    }
                });

                // Add functions to set device preview
                editor.Commands.add('set-device-desktop', {
                    run: editor => editor.setDevice('Escritorio')
                });
                editor.Commands.add('set-device-tablet', {
                    run: editor => editor.setDevice('Tablet')
                });
                editor.Commands.add('set-device-mobile', {
                    run: editor => editor.setDevice('Móvil')
                });

                // Add function to view preview in fullscreen
                editor.Commands.add('fullscreen', {
                    run: function(editor) {
                        const el = editor.getContainer();
                        if (el.requestFullscreen) el.requestFullscreen();
                        else if (el.webkitRequestFullscreen) el.webkitRequestFullscreen();
                        else if (el.mozRequestFullScreen) el.mozRequestFullScreen();
                        else if (el.msRequestFullscreen) el.msRequestFullscreen();
                    }
                });

                // Add function to show or hide layers panel
                editor.Commands.add('show-layers', {
                    getLayersEl(editor) {
                        return editor.getContainer().closest('.editor-row').querySelector(
                            '.panel__right #layers-container');
                    },
                    run(editor, sender) {
                        const lmEl = this.getLayersEl(editor);
                        lmEl.style.display = '';
                    },
                    stop(editor, sender) {
                        const lmEl = this.getLayersEl(editor);
                        lmEl.style.display = 'none';
                    }
                });

                // Add function to show or hide styles panel
                editor.Commands.add('show-styles', {
                    run(editor, sender) {
                        const styleEl = editor.getContainer().closest('.editor-row').querySelector(
                            '.panel__right #styles-container');
                        styleEl.style.display = '';
                    },
                    stop(editor, sender) {
                        const styleEl = editor.getContainer().closest('.editor-row').querySelector(
                            '.panel__right #styles-container');
                        styleEl.style.display = 'none';
                    }
                });

                // Add function to show or hide right panels
                editor.Commands.add('show-traits', {
                    run(editor, sender) {
                        const traitEl = editor.getContainer().closest('.editor-row').querySelector(
                            '.panel__right #traits-container');
                        traitEl.style.display = '';
                    },
                    stop(editor, sender) {
                        const traitEl = editor.getContainer().closest('.editor-row').querySelector(
                            '.panel__right #traits-container');
                        traitEl.style.display = 'none';
                    }
                });

                editor.BlockManager.add('image', {
                    label: 'Image',
                    category: 'Basic',
                    attributes: {
                        class: 'gjs-fonts gjs-f-image'
                    },
                    content: {
                        type: 'image',
                        style: {
                            color: 'black'
                        },
                        activeOnRender: true
                    },
                });

                editor.Commands.add('open-assets', {
                    run(editor, sender) {
                        const modal = editor.Modal;
                        modal.open({
                            title: 'Seleccionar imagen',
                            content: `
                                <div class="container">
                                    <form id="upload-form" class="mb-4">
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="image-upload" accept="image/*">
                                            <button class="btn btn-primary" type="submit">Subir</button>
                                        </div>
                                    </form>
                                    <div id="asset-list" class="row g-3"></div>
                                </div>
                            `
                        });

                        document.getElementById('upload-form').addEventListener('submit', async (e) => {
                            e.preventDefault();
                            const formData = new FormData();
                            formData.append('image', document.getElementById('image-upload').files[
                                0]);

                            try {
                                const response = await fetch('/upload-image', {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                    }
                                });
                                const data = await response.json();
                                if (data.success) {
                                    updateAssetList();
                                }
                            } catch (error) {
                                console.error('Error uploading image:', error);
                            }
                        });

                        function updateAssetList() {
                            fetch('/get-images')
                                .then(response => response.json())
                                .then(images => {
                                    const assetList = document.getElementById('asset-list');
                                    assetList.innerHTML = images.map(img => `
                                        <div class="col-md-3 col-sm-4 col-6">
                                            <img src="${img.url}" alt="${img.name}" class="img-fluid img-thumbnail" style="cursor: pointer; height: 150px; object-fit: cover;" onclick="selectImage('${img.url}')">
                                        </div>
                                    `).join('');
                                });
                        }

                        updateAssetList();

                        window.selectImage = (url) => {
                            editor.getSelected().set('src', url);
                            modal.close();
                        };
                    }
                });

                // Modificar el componente de imagen para usar nuestro comando personalizado
                editor.on('component:selected', (component) => {
                    if (component.get('type') === 'image') {
                        const toolbar = component.get('toolbar');
                        const hasOpenAssetsButton = toolbar.some(item => item.command === 'open-assets');

                        if (!hasOpenAssetsButton) {
                            component.set('toolbar', [
                                ...toolbar,
                                {
                                    attributes: {
                                        class: 'fa fa-folder-open'
                                    },
                                    command: 'open-assets',
                                }
                            ]);
                        }
                    }
                });

                // Add functions to asociated button edit code
                function formatCode(code, type) {
                    if (type === 'html') {
                        return html_beautify(code, {
                            indent_size: 2,
                            wrap_line_length: 80,
                            preserve_newlines: true,
                            max_preserve_newlines: 2
                        });
                    } else if (type === 'css') {
                        return css_beautify(code, {
                            indent_size: 2
                        });
                    }
                    return code;
                }

                editor.Commands.add('edit-code', {
                    run: function(editor, sender) {
                        sender && sender.set('active', 0);
                        const htmlContent = formatCode(editor.getHtml(), 'html');
                        const cssContent = formatCode(editor.getCss(), 'css');
                        editor.Modal.setTitle('Editar HTML')
                            .setContent(`
                            <div class="editor-container">
                                <h3>HTML:</h3>
                                <textarea id="html-edit"></textarea>
                                <h3>CSS (solo lectura):</h3>
                                <textarea id="css-view"></textarea>
                                <button id="apply-changes" class="btn btn-primary mt-3">Aplicar Cambios</button>
                            </div>
                        `)
                            .open();

                        const htmlEditor = CodeMirror.fromTextArea(document.getElementById('html-edit'), {
                            mode: 'htmlmixed',
                            theme: 'monokai',
                            lineNumbers: true,
                            indentUnit: 2,
                            tabSize: 2,
                            autoCloseTags: true,
                            autoCloseBrackets: true,
                            matchBrackets: true
                        });
                        htmlEditor.setValue(htmlContent);

                        CodeMirror.fromTextArea(document.getElementById('css-view'), {
                            mode: 'css',
                            theme: 'monokai',
                            lineNumbers: true,
                            readOnly: true
                        }).setValue(cssContent);

                        document.getElementById('apply-changes').addEventListener('click', function() {
                            const newHtml = htmlEditor.getValue();
                            editor.setComponents(newHtml);
                            editor.Modal.close();
                        });
                    }
                });

                editor.BlockManager.add('internal-link', {
                    label: 'Enlace Interno',
                    category: 'Basic',
                    content: {
                        type: 'link',
                        content: 'Enlace Interno',
                        classes: ['internal-link']
                    },
                    attributes: {
                        class: 'fa fa-link'
                    }
                });

                editor.on('block:drag:stop', (component) => {
                    if (component.get('type') === 'link' && component.getClasses().includes('internal-link')) {
                        openInternalLinkModal(component);
                    }
                });

                function openInternalLinkModal(component) {
                    // Implementa un modal para seleccionar una página interna
                    component.set('attributes', {
                        href: '<?php echo e(route('pages.display', ':slug')); ?>'.replace(':slug', selectedPageSlug)
                    });
                }

                // Add function to apply button, apply changes from editor
                document.getElementById('apply-code').addEventListener('click', function() {
                    const htmlEditor = document.getElementById('html-editor');
                    const cssEditor = document.getElementById('css-editor');

                    const htmlCM = CodeMirror.fromTextArea(htmlEditor);
                    const cssCM = CodeMirror.fromTextArea(cssEditor);

                    editor.setComponents(htmlCM.getValue());
                    editor.setStyle(cssCM.getValue());

                    document.querySelector('.code-editor').style.display = 'none';
                });

                // Add function to close button, close editor
                document.getElementById('close-editor').addEventListener('click', function() {
                    document.querySelector('.code-editor').style.display = 'none';
                });

                // Add command save function to store or update page
                editor.Commands.add('save-page', {
                    run: function(editor) {
                        const components = editor.getComponents();
                        const serializedComponents = editor.getComponents();
                        const htmlContent = editor.getHtml();
                        const cssStyles = editor.getCss();

                        // Extract plugin information from components
                        const usedPlugins = editor.getWrapper().find('[data-gjs-type]').map(component => {
                            const type = component.get('type');
                            const plugin = availablePlugins.find(p => p.name === type);
                            if (plugin) {
                                return {
                                    id: plugin.id,
                                    name: plugin.name,
                                    view: component.get('traits')?.where({
                                        name: 'view'
                                    })[0]?.get('value') || 'index'
                                };
                            }
                            return null;
                        }).filter(Boolean);

                        // Get page data
                        let name = '<?php echo e($page->name ?? ''); ?>';
                        let description = '<?php echo e($page->description ?? ''); ?>';

                        if (!name) {
                            name = prompt("Ingrese el nombre de la pagina:", "Nueva Página");
                            if (!name) {
                                alert('El nombre de la página es requerido.');
                                return;
                            }
                        }

                        if (!description) {
                            description = prompt("Ingrese la descripción de la página:", "");
                        }

                        // Open save modal
                        var saveModal = new bootstrap.Modal(document.getElementById('saveModal'));
                        saveModal.show();

                        // Maneja el click en el botón de confirmar guardar
                        document.getElementById('confirm-save').onclick = function() {
                            const versionType = document.getElementById('save-version-type').value;

                            const pageData = {
                                name: name,
                                description: description,
                                content: htmlContent,
                                serialized_content: JSON.stringify(serializedComponents),
                                template_id: <?php echo e($page->template_id ?? 1); ?>,
                                user_id: <?php echo e(auth()->id() ?? 'null'); ?>,
                                status: 'draft',
                                active_plugins: usedPlugins.map(p => p.id),
                                plugin_data: usedPlugins,
                                version_type: versionType
                            };

                            fetch('<?php echo e(isset($page->id) ? route('pages.update', $page->id) : route('pages.store')); ?>', {
                                    method: '<?php echo e(isset($page->id) ? 'PUT' : 'POST'); ?>',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                                    },
                                    body: JSON.stringify(pageData)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        saveModal.hide();
                                        var successModal = new bootstrap.Modal(document
                                            .getElementById('successModal'));
                                        successModal.show();
                                        document.getElementById('successModalOk').onclick =
                                            function() {
                                                window.location.href =
                                                    '<?php echo e(route('pages.index')); ?>';
                                            };
                                    } else {
                                        throw new Error('Error al guardar página');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    saveModal.hide();
                                    var errorModal = new bootstrap.Modal(document.getElementById(
                                        'errorModal'));
                                    document.getElementById('errorModalBody').textContent =
                                        'Error al guardar página: ' + error.message;
                                    errorModal.show();
                                });
                        };
                    }
                });

                // Set preview frame on load
                editor.on('load', () => {
                    const iframe = editor.Canvas.getFrameEl();
                    iframe.style.width = '100%';
                    iframe.style.height = '100%';

                    editor.setStyle(<?php echo json_encode($defaultStyles); ?>);

                    // Si hay contenido serializado, cárgalo
                    <?php if(isset($page->serialized_content)): ?>
                        const components = <?php echo $page->serialized_content; ?>;
                        editor.setComponents(components);
                        console.log('Contenido serializado cargado:', components);
                    <?php elseif(isset($page->content)): ?>
                        editor.setComponents(<?php echo json_encode($page->content); ?>);
                        console.log('Contenido HTML cargado');
                    <?php endif; ?>

                    console.log('Plugins disponibles:', availablePlugins);

                    if (activePlugins && activePlugins.length > 0) {
                        console.log('Cargando plugins activos:', activePlugins);
                        activePlugins.forEach(pluginId => {
                            const plugin = availablePlugins.find(p => p.id === pluginId);
                            if (plugin) {
                                console.log('Cargando plugin activo:', plugin.name);
                            }
                        });
                    }

                    console.log('Editor cargado con contenido existente');
                });

                function formatPluginName(name) {
                    return name.split(' ')
                        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                        .join('');
                }

                // Function to generate unique ID
                const generateUniqueId = (componentType) => {
                    return `gjs-${componentType}-${Math.random().toString(36).substr(2, 9)}`;
                };

                // Function to comfirm component has unique ID
                const ensureUniqueId = (component) => {
                    let id = component.get('attributes').id;
                    if (!id) {
                        id = generateUniqueId(component.get('type'));
                        component.addAttributes({
                            id: id
                        });
                    }
                    return id;
                };

                // Función para actualizar los traits de ID y clase
                const updateIdAndClassTraits = (component) => {
                    const id = ensureUniqueId(component);
                    const classes = component.getClasses().join(' ');

                    component.set('traits', [{
                            type: 'text',
                            label: 'id',
                            name: 'id',
                            value: id
                        },
                        {
                            type: 'text',
                            label: 'classes',
                            name: 'class',
                            value: classes
                        },
                        ...component.get('traits').filter(trait => trait.name !== 'id' && trait.name !==
                            'class')
                    ]);
                };

                // Manage component seelction
                editor.on('component:selected', (component) => {
                    updateIdAndClassTraits(component);
                });

                // Manage component creation
                editor.on('component:create', (component) => {
                    ensureUniqueId(component);
                    updateIdAndClassTraits(component);
                });

                // Manaje changes with traits
                editor.on('trait:change', (component, trait) => {
                    if (trait.get('name') === 'id') {
                        const newId = trait.get('value');
                        component.addAttributes({
                            id: newId
                        });
                    } else if (trait.get('name') === 'class') {
                        const newClasses = trait.get('value').split(' ');
                        component.setClass(newClasses);
                    }
                });

                // Modificar el StyleManager para trabajar con clases e IDs
                editor.on('styleManager:update', (styleManager) => {
                    const selectedComponent = editor.getSelected();
                    if (selectedComponent) {
                        const id = selectedComponent.get('attributes').id;
                        const classes = selectedComponent.getClasses();
                        let selector = '';

                        if (id) {
                            selector += `#${id}`;
                        }
                        if (classes.length > 0) {
                            selector += (selector ? ', ' : '') + '.' + classes.join('.');
                        }

                        if (selector) {
                            styleManager.setTarget(selector);
                        }
                    }
                });

                // Función para aplicar estilos a componentes con las mismas clases
                const applyStylesToSharedClasses = (component) => {
                    const classes = component.getClasses();
                    if (classes.length > 0) {
                        const selector = '.' + classes.join('.');
                        const sharedComponents = editor.Components.getWrapper().find(selector);
                        const styles = component.getStyle();
                        sharedComponents.forEach(comp => {
                            if (comp !== component) {
                                comp.setStyle(styles);
                            }
                        });
                    }
                };

                // Aplicar estilos compartidos cuando se modifica un componente
                editor.on('component:styleUpdate', (component) => {
                    applyStylesToSharedClasses(component);
                });

                // Añadir botón para generar nuevo ID
                editor.Commands.add('generate-new-id', {
                    run: (editor, sender) => {
                        const selectedComponent = editor.getSelected();
                        if (selectedComponent) {
                            const newId = generateUniqueId(selectedComponent.get('type'));
                            selectedComponent.addAttributes({
                                id: newId
                            });
                            const idTrait = selectedComponent.get('traits').filter(trait => trait.get(
                                'name') === 'id')[0];
                            if (idTrait) {
                                idTrait.set('value', newId);
                            }
                        }
                    }
                });

                // Añadir el botón a la barra de herramientas
                editor.Panels.addButton('options', {
                    id: 'generate-new-id',
                    className: 'fa fa-refresh',
                    command: 'generate-new-id',
                    attributes: {
                        title: 'Generate New ID'
                    }
                });
            });
        </script>
    </body>

</html>
<?php /**PATH C:\Users\brian\Documentos\GitHub\bankhipo\website-institucional\bhcms\resources\views/pages/editor.blade.php ENDPATH**/ ?>