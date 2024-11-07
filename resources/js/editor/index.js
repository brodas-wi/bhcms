import { editorConfig } from './config/editor';
import { initializePluginSystem } from './config/plugins';
import { initializeCommands } from './commands';
import { setupEventListeners } from './utils/events';
import { KeyboardShortcuts } from './utils/keyboard';
import { ComponentManager } from './utils/components';
import { EditorNotifications } from './utils/notifications';

class Editor {
    constructor() {
        this.editor = null;
        this.notifications = new EditorNotifications();
        this.componentManager = null;
        this.keyboardShortcuts = null;
    }

    initialize() {
        try {
            // Inicializar el editor principal
            this.editor = grapesjs.init({
                ...editorConfig,
                container: '#gjs',
                height: '100%',
                width: 'auto',
                storageManager: false,
                plugins: ['gjs-blocks-basic'],
                canvas: {
                    styles: [
                        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css'
                    ],
                    scripts: [
                        'https://code.jquery.com/jquery-3.5.1.slim.min.js',
                        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js'
                    ]
                },
                blockManager: {
                    appendTo: '#blocks',
                    blocks: [
                        ...this.getDefaultBlocks(),
                        ...this.getPluginBlocks()
                    ]
                }
            });

            // Inicializar subsistemas
            this.initializeSubsystems();

            // Configurar eventos
            this.setupEvents();

            // Cargar contenido inicial
            this.loadInitialContent();

            this.notifications.success('Editor iniciado correctamente');
        } catch (error) {
            console.error('Error al inicializar el editor:', error);
            this.notifications.error('Error al inicializar el editor');
        }
    }

    initializeSubsystems() {
        // Inicializar el sistema de plugins
        initializePluginSystem(this.editor);

        // Inicializar comandos
        initializeCommands(this.editor);

        // Inicializar el gestor de componentes
        this.componentManager = new ComponentManager(this.editor);

        // Inicializar atajos de teclado
        this.keyboardShortcuts = new KeyboardShortcuts(this.editor);

        // Configurar paneles
        this.setupPanels();
    }

    setupEvents() {
        setupEventListeners(this.editor);

        // Eventos de guardado
        this.editor.on('storage:start', () => {
            this.notifications.info('Guardando cambios...');
        });

        this.editor.on('storage:end', () => {
            this.notifications.success('Cambios guardados');
        });

        // Eventos de error
        this.editor.on('error', (error) => {
            this.notifications.error(`Error: ${error.message}`);
        });

        // Cambios sin guardar
        window.addEventListener('beforeunload', (event) => {
            if (this.editor.getDirtyCount() > 0) {
                event.preventDefault();
                event.returnValue = ''; // Required for Chrome
            }
        });
    }

    setupPanels() {
        this.editor.Panels.addPanel({
            id: 'devices-buttons',
            buttons: [
                {
                    id: 'device-desktop',
                    label: '<i class="fa fa-desktop"></i>',
                    command: 'set-device-desktop',
                    active: true,
                    togglable: false
                },
                {
                    id: 'device-tablet',
                    label: '<i class="fa fa-tablet"></i>',
                    command: 'set-device-tablet',
                    togglable: false
                },
                {
                    id: 'device-mobile',
                    label: '<i class="fa fa-mobile"></i>',
                    command: 'set-device-mobile',
                    togglable: false
                }
            ]
        });

        this.editor.Panels.addPanel({
            id: 'basic-actions',
            buttons: [
                {
                    id: 'visibility',
                    active: true,
                    className: 'btn-toggle-borders',
                    label: '<i class="fa fa-clone"></i>',
                    command: 'sw-visibility'
                },
                {
                    id: 'export',
                    className: 'btn-open-export',
                    label: '<i class="fa fa-code"></i>',
                    command: 'export-template'
                },
                {
                    id: 'show-json',
                    className: 'btn-show-json',
                    label: '<i class="fa fa-file-code-o"></i>',
                    context: 'show-json',
                    command: 'show-json'
                },
                {
                    id: 'save',
                    className: 'btn-save',
                    label: '<i class="fa fa-floppy-o"></i>',
                    command: 'save-page'
                }
            ]
        });
    }

    getDefaultBlocks() {
        return [
            {
                id: 'section',
                label: '<i class="fa fa-square-o"></i><div>Sección</div>',
                category: 'Basic',
                content: `<section class="section">
                    <div class="container">
                        <div class="row">
                            <div class="col-12"></div>
                        </div>
                    </div>
                </section>`
            },
            {
                id: 'text',
                label: '<i class="fa fa-text-width"></i><div>Texto</div>',
                category: 'Basic',
                content: '<div data-gjs-type="text">Insertar texto aquí</div>'
            },
            {
                id: 'image',
                label: '<i class="fa fa-picture-o"></i><div>Imagen</div>',
                category: 'Basic',
                content: { type: 'image' }
            },
            {
                id: 'video',
                label: '<i class="fa fa-youtube-play"></i><div>Video</div>',
                category: 'Basic',
                content: {
                    type: 'video',
                    src: 'embed/video',
                    style: {
                        height: '350px',
                        width: '100%'
                    }
                }
            }
        ];
    }

    getPluginBlocks() {
        return window.editorConfig.availablePlugins.map(plugin => ({
            id: plugin.name,
            label: `
                <div class="plugin-block">
                    <i class="fas fa-${plugin.icon || 'puzzle-piece'}"></i>
                    <div>${plugin.original_name}</div>
                </div>
            `,
            category: 'Plugins',
            content: { type: plugin.name }
        }));
    }

    loadInitialContent() {
        // Cargar contenido serializado si existe
        if (window.editorConfig.serializedContent) {
            this.editor.setComponents(window.editorConfig.serializedContent);
        }

        // Establecer estilos por defecto
        if (window.editorConfig.defaultStyles) {
            this.editor.setStyle(window.editorConfig.defaultStyles);
        }

        // Cargar plugins activos
        if (window.editorConfig.activePlugins?.length > 0) {
            window.editorConfig.activePlugins.forEach(pluginId => {
                const plugin = window.editorConfig.availablePlugins.find(p => p.id === pluginId);
                if (plugin) {
                    console.log('Cargando plugin:', plugin.name);
                }
            });
        }
    }

    destroy() {
        if (this.editor) {
            // Limpiar eventos
            this.editor.off();

            // Destruir editor
            this.editor.destroy();

            // Limpiar referencias
            this.editor = null;
            this.componentManager = null;
            this.keyboardShortcuts = null;
        }
    }
}

// Inicializar el editor cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    const editorInstance = new Editor();
    editorInstance.initialize();

    // Exponer la instancia globalmente para debugging si es necesario
    if (process.env.NODE_ENV !== 'production') {
        window.__editor = editorInstance;
    }
});

// Exportar la clase Editor para uso en otros módulos
export default Editor;
