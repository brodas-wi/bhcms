<!-- resources/views/admin/templates/editor.blade.php -->
<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ isset($template->name) ? 'Editar Plantilla: ' . $template->name : 'Crear Nueva Plantilla' }}</title>
        <link rel="stylesheet" href="https://unpkg.com/grapesjs@0.20.3/dist/css/grapes.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
            integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldcode.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/foldgutter.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/brace-fold.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/xml-fold.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/fold/comment-fold.min.js"></script>
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

            .code-editor {
                position: fixed;
                top: 50px;
                right: 0;
                width: 50%;
                height: calc(100% - 50px);
                z-index: 100;
                background-color: #f5f5f5;
                display: none;
                flex-direction: column;
                padding: 20px;
                box-sizing: border-box;
                overflow: hidden;
            }

            .code-editor h3 {
                margin-top: 0;
                margin-bottom: 10px;
                font-size: 18px;
                color: #333;
            }

            .code-editor-content {
                display: flex;
                flex-direction: column;
                height: calc(100% - 80px);
                overflow: hidden;
            }

            .editor-section {
                display: flex;
                flex-direction: column;
                flex: 1;
                margin-bottom: 20px;
                overflow: hidden;
            }

            .code-editor pre {
                margin: 0;
                flex-grow: 1;
                overflow: auto;
                background-color: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
            }

            .code-editor pre code {
                font-family: monospace;
                font-size: 14px;
                white-space: pre-wrap;
                word-wrap: break-word;
                padding: 10px;
                display: block;
                min-height: 100%;
                box-sizing: border-box;
            }

            .code-editor-buttons {
                display: flex;
                justify-content: flex-end;
                margin-top: 20px;
            }

            .code-editor-buttons button {
                margin-left: 10px;
                padding: 10px 20px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 14px;
                transition: background-color 0.3s ease;
            }

            .code-editor-buttons button:hover {
                background-color: #45a049;
            }

            .code-editor-buttons button:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.5);
            }

            #close-editor {
                background-color: #f44336;
            }

            #close-editor:hover {
                background-color: #d32f2f;
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

            /* Mejoras en la scrollbar para el editor de código */
            .code-editor pre::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }

            .code-editor pre::-webkit-scrollbar-thumb {
                background-color: #888;
                border-radius: 4px;
            }

            .code-editor pre::-webkit-scrollbar-track {
                background-color: #f1f1f1;
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
                    <pre><code id="html-editor" class="language-html" contenteditable="true"></code></pre>
                </div>
                <div class="editor-section">
                    <h3>CSS</h3>
                    <pre><code id="css-editor" class="language-css" contenteditable="true"></code></pre>
                </div>
            </div>
            <div class="code-editor-buttons">
                <button id="apply-code">Aplicar Cambios</button>
                <button id="close-editor">Cerrar Editor</button>
            </div>
        </div>

        <script src="https://unpkg.com/grapesjs@0.20.3"></script>
        <script src="https://unpkg.com/grapesjs-blocks-basic@1.0.1"></script>
        <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const editor = grapesjs.init({
                    container: '#gjs',
                    height: '100%',
                    width: 'auto',
                    storageManager: false,
                    canvas: {
                        styles: ['https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'],
                        scripts: [
                            'https://code.jquery.com/jquery-3.5.1.slim.min.js',
                            'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
                        ],
                        dragMode: 'absolute',
                        editHtml: false,
                    },
                    layerManager: {
                        appendTo: '#layers-container'
                    },
                    styleManager: {
                        appendTo: '#styles-container',
                        sectors: [{
                                name: 'Dimensión',
                                open: false,
                                buildProps: ['width', 'height', 'max-width', 'min-height', 'margin',
                                    'padding'
                                ],
                            },
                            {
                                name: 'Tipografía',
                                open: false,
                                properties: [
                                    'font-family',
                                    'font-size',
                                    {
                                        name: 'Grosor de letra',
                                        property: 'font-weight',
                                        type: 'select',
                                        defaults: '400',
                                        options: [{
                                                value: '100',
                                                name: 'Fino'
                                            },
                                            {
                                                value: '200',
                                                name: 'Extra Ligero'
                                            },
                                            {
                                                value: '300',
                                                name: 'Ligero'
                                            },
                                            {
                                                value: '400',
                                                name: 'Normal'
                                            },
                                            {
                                                value: '500',
                                                name: 'Medio'
                                            },
                                            {
                                                value: '600',
                                                name: 'Semi Negrita'
                                            },
                                            {
                                                value: '700',
                                                name: 'Negrita'
                                            },
                                            {
                                                value: '800',
                                                name: 'Extra Negrita'
                                            },
                                            {
                                                value: '900',
                                                name: 'Negro'
                                            }
                                        ]
                                    },
                                    'letter-spacing',
                                    'color',
                                    'line-height',
                                    {
                                        name: 'Alineación de texto',
                                        property: 'text-align',
                                        type: 'radio',
                                        defaults: 'left',
                                        options: [{
                                                value: 'left',
                                                name: 'Izquierda'
                                            },
                                            {
                                                value: 'center',
                                                name: 'Centro'
                                            },
                                            {
                                                value: 'right',
                                                name: 'Derecha'
                                            },
                                            {
                                                value: 'justify',
                                                name: 'Justificado'
                                            }
                                        ]
                                    },
                                    {
                                        name: 'Decoración de texto',
                                        property: 'text-decoration',
                                        defaults: 'none',
                                    },
                                    'text-shadow'
                                ],
                            },
                            {
                                name: 'Decoración',
                                open: false,
                                buildProps: ['background-color', 'border', 'border-radius', 'box-shadow'],
                            },
                        ],
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
                                    },
                                    {
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
                                        id: 'export',
                                        className: 'btn-open-export',
                                        label: '<i class="fa fa-file-code-o"></i>',
                                        command: 'export-template',
                                        attributes: {
                                            title: 'Ver código'
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
                                        command: 'save-template',
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
                                    properties: {
                                        'width': 'Ancho',
                                        'height': 'Alto',
                                        'max-width': 'Ancho máximo',
                                        'min-height': 'Alto mínimo',
                                        'margin': 'Margen',
                                        'padding': 'Relleno',
                                        'font-family': 'Tipo de letra',
                                        'font-size': 'Tamaño de letra',
                                        'letter-spacing': 'Espaciado entre letras',
                                        'color': 'Color de texto',
                                        'line-height': 'Altura de línea',
                                        'text-shadow': 'Sombra de texto',
                                        'background-color': 'Color de fondo',
                                        'border': 'Borde',
                                        'border-radius': 'Radio de borde',
                                        'box-shadow': 'Sombra de caja'
                                    }
                                },
                                traitManager: {
                                    empty: 'Seleccione un elemento para usar el Administrador de rasgos',
                                    label: 'Ajustes de componentes',
                                },
                            }
                        }
                    },
                    components: {
                        defaults: {
                            traits: [{
                                    type: 'text',
                                    label: 'id',
                                    name: 'id',
                                },
                                {
                                    type: 'text',
                                    label: 'classes',
                                    name: 'class',
                                }
                            ]
                        },
                    }
                });

                // Add function to back button, return to templates index
                editor.Commands.add('back-to-index', {
                    run: function(editor, sender) {
                        if (confirm(
                                '¿Estás seguro de que quieres salir? Los cambios no guardados se perderán.'
                            )) {
                            window.location.href = '{{ route('templates.index') }}';
                        }
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

                // Add functions to associated button edit code
                editor.Commands.add('edit-code', {
                    run: function(editor, sender) {
                        const htmlEditor = document.getElementById('html-editor');
                        const cssEditor = document.getElementById('css-editor');
                        const codeEditor = document.querySelector('.code-editor');

                        // Format and prettier HTML
                        const formattedHtml = html_beautify(editor.getHtml(), {
                            indent_size: 2,
                            wrap_line_length: 80,
                            preserve_newlines: true,
                            max_preserve_newlines: 2
                        });

                        // Format and prettier CSS
                        const formattedCss = css_beautify(editor.getCss(), {
                            indent_size: 2
                        });

                        htmlEditor.textContent = formattedHtml;
                        cssEditor.textContent = formattedCss;

                        Prism.highlightElement(htmlEditor);
                        Prism.highlightElement(cssEditor);

                        codeEditor.style.display = 'flex';
                    }
                });

                function cleanupCSS(css) {
                    const styleSheet = document.createElement('style');
                    styleSheet.textContent = css;
                    document.head.appendChild(styleSheet);

                    const rules = styleSheet.sheet.cssRules;
                    const consolidatedRules = {};

                    for (let rule of rules) {
                        if (rule.type === CSSRule.STYLE_RULE) {
                            if (!consolidatedRules[rule.selectorText]) {
                                consolidatedRules[rule.selectorText] = {};
                            }
                            for (let i = 0; i < rule.style.length; i++) {
                                const property = rule.style[i];
                                consolidatedRules[rule.selectorText][property] = rule.style.getPropertyValue(property);
                            }
                        }
                    }

                    document.head.removeChild(styleSheet);

                    let cleanCSS = '';
                    for (let selector in consolidatedRules) {
                        cleanCSS += `${selector} {\n`;
                        for (let property in consolidatedRules[selector]) {
                            cleanCSS += `  ${property}: ${consolidatedRules[selector][property]};\n`;
                        }
                        cleanCSS += '}\n\n';
                    }

                    return cleanCSS;
                }

                // Add function to apply button, apply changes from editor
                document.getElementById('apply-code').addEventListener('click', function() {
                    const htmlEditor = document.getElementById('html-editor');
                    const cssEditor = document.getElementById('css-editor');

                    editor.setComponents(htmlEditor.textContent);
                    editor.setStyle(cssEditor.textContent);
                });

                // Add function to close button, close editor
                document.getElementById('close-editor').addEventListener('click', function() {
                    document.querySelector('.code-editor').style.display = 'none';
                });

                // Add command save function to store or update template
                editor.Commands.add('save-template', {
                    run: function(editor) {
                        const dirtyCSS = editor.getCss();
                        const cleanCSS = cleanupCSS(dirtyCSS);

                        // Get name and description if exists
                        let name = '{{ $template->name ?? '' }}';
                        let description = '{{ $template->description ?? '' }}';
                        const userId = '{{ Auth::id() }}';
                        const isDefault = '0';
                        const folderId = null;
                        const thumbnail = null;

                        // Request name is not exists
                        if (!name) {
                            name = prompt("Ingrese el nombre de la plantilla:", "Nueva Plantilla");

                            // Request name if value null, field can not be null
                            if (!name) {
                                alert('El nombre de la plantilla es requerido.');
                                return;
                            }
                        }

                        // Request description is not exists
                        if (!description) {
                            description = prompt("Ingrese la descripción de la plantilla:", "");
                        }

                        // Fetch case for save or update template
                        fetch('{{ isset($template->id) ? route('templates.update', $template->id) : route('templates.store') }}', {
                                method: '{{ isset($template->id) ? 'PUT' : 'POST' }}',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    name: name,
                                    description: description,
                                    styles: cleanCSS,
                                    user_id: userId,
                                    is_default: isDefault,
                                    folder_id: folderId,
                                    thumbnail: thumbnail
                                })
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Error en la respuesta del servidor');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    alert('Plantilla guardada exitosamente');

                                    window.location.href = '{{ route('templates.index') }}';
                                } else {
                                    throw new Error('Error al guardar la plantilla');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Error al guardar la plantilla: ' + error.message);
                            });
                    }
                });

                // Set preview frame on load
                editor.on('load', () => {
                    const iframe = editor.Canvas.getFrameEl();
                    iframe.style.width = '100%';
                    iframe.style.height = '100%';

                    editor.setComponents({!! json_encode($defaultContent) !!});

                    // If styles not null, load styles from template
                    @if (isset($template->styles))
                        editor.setStyle({!! json_encode($template->styles) !!});
                    @endif
                });

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
