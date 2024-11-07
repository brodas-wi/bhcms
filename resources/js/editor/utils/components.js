export class ComponentManager {
    constructor(editor) {
        this.editor = editor;
        this.setupComponentTypes();
        this.setupComponentEvents();
    }

    setupComponentTypes() {
        // Register basic components
        this.registerBasicComponents();

        // Register custom components
        this.registerCustomComponents();
    }

    registerBasicComponents() {
        const basicComponents = {
            'container': {
                isComponent: el => el.tagName === 'DIV',
                model: {
                    defaults: {
                        tagName: 'div',
                        droppable: true,
                        traits: [
                            'id',
                            'class',
                            {
                                type: 'select',
                                label: 'Alineación',
                                name: 'text-align',
                                options: [
                                    { value: 'left', name: 'Izquierda' },
                                    { value: 'center', name: 'Centro' },
                                    { value: 'right', name: 'Derecha' },
                                    { value: 'justify', name: 'Justificado' }
                                ]
                            }
                        ]
                    }
                }
            },
            'section': {
                isComponent: el => el.tagName === 'SECTION',
                model: {
                    defaults: {
                        tagName: 'section',
                        droppable: true,
                        traits: ['id', 'class']
                    }
                }
            },
            'link': {
                isComponent: el => el.tagName === 'A',
                model: {
                    defaults: {
                        tagName: 'a',
                        droppable: true,
                        traits: [
                            'id',
                            'class',
                            'href',
                            'title',
                            {
                                type: 'select',
                                label: 'Target',
                                name: 'target',
                                options: [
                                    { value: '_self', name: 'Misma ventana' },
                                    { value: '_blank', name: 'Nueva ventana' }
                                ]
                            }
                        ]
                    }
                }
            }
        };

        Object.entries(basicComponents).forEach(([type, definition]) => {
            this.editor.DomComponents.addType(type, definition);
        });
    }

    registerCustomComponents() {
        // Responsive Container
        this.editor.DomComponents.addType('responsive-container', {
            model: {
                defaults: {
                    tagName: 'div',
                    classes: ['container-fluid'],
                    droppable: true,
                    traits: [
                        'id',
                        'class',
                        {
                            type: 'select',
                            label: 'Tipo',
                            name: 'container-type',
                            options: [
                                { value: 'container-fluid', name: 'Fluido' },
                                { value: 'container', name: 'Fijo' }
                            ],
                            changeProp: true
                        }
                    ]
                }
            }
        });

        // Row Component
        this.editor.DomComponents.addType('row', {
            model: {
                defaults: {
                    tagName: 'div',
                    classes: ['row'],
                    droppable: true,
                    traits: [
                        'id',
                        'class',
                        {
                            type: 'select',
                            label: 'Alineación',
                            name: 'justify-content',
                            options: [
                                { value: 'start', name: 'Inicio' },
                                { value: 'center', name: 'Centro' },
                                { value: 'end', name: 'Fin' },
                                { value: 'between', name: 'Espacio entre' },
                                { value: 'around', name: 'Espacio alrededor' }
                            ]
                        }
                    ]
                }
            }
        });

        // Column Component
        this.editor.DomComponents.addType('column', {
            model: {
                defaults: {
                    tagName: 'div',
                    classes: ['col'],
                    droppable: true,
                    traits: [
                        'id',
                        'class',
                        {
                            type: 'number',
                            label: 'Columnas (1-12)',
                            name: 'col-size',
                            min: 1,
                            max: 12,
                            value: 12
                        }
                    ]
                }
            }
        });
    }

    setupComponentEvents() {
        // Component selection
        this.editor.on('component:selected', this.handleComponentSelection.bind(this));

        // Component changes
        this.editor.on('component:update', this.handleComponentUpdate.bind(this));

        // Component removal
        this.editor.on('component:remove', this.handleComponentRemoval.bind(this));
    }

    handleComponentSelection(component) {
        if (!component) return;

        // Update style manager
        this.updateStyleManager(component);

        // Update traits manager
        this.updateTraitsManager(component);

        // Highlight component
        this.highlightComponent(component);
    }

    handleComponentUpdate(component) {
        // Save state
        this.editor.store();

        // Update preview
        this.updatePreview(component);
    }

    handleComponentRemoval(component) {
        // Clean up any resources
        this.cleanupComponent(component);

        // Update editor state
        this.editor.store();
    }

    updateStyleManager(component) {
        const styleManager = this.editor.StyleManager;
        const componentType = component.get('type');

        // Update available styles based on component type
        styleManager.getProperty('dimension', 'width').setValue(
            component.getStyle('width') || 'auto'
        );
    }

    updateTraitsManager(component) {
        const traitManager = this.editor.TraitManager;
        const traits = component.get('traits');

        // Update traits panel
        traitManager.setTarget(component);
    }

    highlightComponent(component) {
        const view = component.getView();
        if (!view) return;

        // Remove previous highlights
        this.editor.getWrapper().find('.highlight').forEach(comp => {
            comp.removeClass('highlight');
        });

        // Add highlight class
        component.addClass('highlight');
    }

    updatePreview(component) {
        // Update canvas
        this.editor.refresh();

        // Update responsive preview if needed
        if (component.get('responsive')) {
            this.updateResponsivePreview(component);
        }
    }

    cleanupComponent(component) {
        // Remove event listeners
        component.getView()?.remove();

        // Clean up any custom resources
        if (typeof component.cleanup === 'function') {
            component.cleanup();
        }
    }

    updateResponsivePreview(component) {
        const deviceManager = this.editor.DeviceManager;
        const currentDevice = deviceManager.getDeviceByName(
            deviceManager.getSelected()
        );

        if (currentDevice) {
            this.editor.refresh();
        }
    }
}

// Add component styles
const style = document.createElement('style');
style.textContent = `
    .highlight {
        outline: 2px solid #4b9eff !important;
        outline-offset: 1px;
    }
`;
document.head.appendChild(style);
