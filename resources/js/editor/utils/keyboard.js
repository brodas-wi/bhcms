export class KeyboardShortcuts {
    constructor(editor) {
        this.editor = editor;
        this.shortcuts = this.defineShortcuts();
        this.initialize();
    }

    defineShortcuts() {
        return {
            // File operations
            'mod+s': {
                handler: () => this.editor.runCommand('save-page'),
                description: 'Guardar página'
            },
            'mod+p': {
                handler: () => this.editor.runCommand('preview'),
                description: 'Vista previa'
            },

            // Edit operations
            'mod+z': {
                handler: () => this.editor.UndoManager.undo(),
                description: 'Deshacer'
            },
            'mod+shift+z': {
                handler: () => this.editor.UndoManager.redo(),
                description: 'Rehacer'
            },
            'mod+y': {
                handler: () => this.editor.UndoManager.redo(),
                description: 'Rehacer (alternativo)'
            },
            'mod+c': {
                handler: () => this.copySelected(),
                description: 'Copiar'
            },
            'mod+v': {
                handler: () => this.pasteContent(),
                description: 'Pegar'
            },
            'mod+x': {
                handler: () => this.cutSelected(),
                description: 'Cortar'
            },
            'delete': {
                handler: () => this.deleteSelected(),
                description: 'Eliminar'
            },
            'backspace': {
                handler: () => this.deleteSelected(),
                description: 'Eliminar (alternativo)'
            },

            // View operations
            'mod+shift+m': {
                handler: () => this.editor.runCommand('show-layers'),
                description: 'Mostrar/Ocultar capas'
            },
            'mod+shift+s': {
                handler: () => this.editor.runCommand('show-styles'),
                description: 'Mostrar/Ocultar estilos'
            },
            'mod+shift+t': {
                handler: () => this.editor.runCommand('show-traits'),
                description: 'Mostrar/Ocultar atributos'
            },

            // Device preview
            'mod+shift+d': {
                handler: () => this.editor.setDevice('Escritorio'),
                description: 'Vista escritorio'
            },
            'mod+shift+a': {
                handler: () => this.editor.setDevice('Tablet'),
                description: 'Vista tablet'
            },
            'mod+shift+i': {
                handler: () => this.editor.setDevice('Móvil'),
                description: 'Vista móvil'
            },

            // Code editor
            'mod+e': {
                handler: () => this.editor.runCommand('edit-code'),
                description: 'Abrir editor de código'
            }
        };
    }

    initialize() {
        document.addEventListener('keydown', (event) => {
            const shortcut = this.getShortcutFromEvent(event);
            if (shortcut && this.shortcuts[shortcut]) {
                event.preventDefault();
                this.shortcuts[shortcut].handler();
            }
        });
    }

    getShortcutFromEvent(event) {
        const mod = event.metaKey || event.ctrlKey ? 'mod+' : '';
        const shift = event.shiftKey ? 'shift+' : '';
        const key = event.key.toLowerCase();
        return `${mod}${shift}${key}`;
    }

    copySelected() {
        const selected = this.editor.getSelected();
        if (selected && !this.isEditingText(selected)) {
            localStorage.setItem('gjs-copied-component', JSON.stringify(selected.toJSON()));
        }
    }

    async pasteContent() {
        const selected = this.editor.getSelected();
        if (!this.isEditingText(selected)) {
            const copied = localStorage.getItem('gjs-copied-component');
            if (copied) {
                const component = JSON.parse(copied);
                const target = selected || this.editor.getWrapper();
                target.append(component);
            }
        }
    }

    cutSelected() {
        const selected = this.editor.getSelected();
        if (selected && !this.isEditingText(selected)) {
            this.copySelected();
            selected.remove();
        }
    }

    deleteSelected() {
        const selected = this.editor.getSelected();
        if (selected && !this.isEditingText(selected)) {
            selected.remove();
        }
    }

    isEditingText(component) {
        return component && (
            component.get('type') === 'text' ||
            component.get('type') === 'link' ||
            component.view.el.contentEditable === 'true'
        );
    }

    showShortcutsModal() {
        const modal = document.createElement('div');
        modal.className = 'shortcuts-modal modal fade';
        modal.innerHTML = this.getShortcutsModalContent();
        document.body.appendChild(modal);

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();

        modal.addEventListener('hidden.bs.modal', () => {
            modal.remove();
        });
    }

    getShortcutsModalContent() {
        const shortcuts = Object.entries(this.shortcuts)
            .map(([key, { description }]) => `
                <tr>
                    <td><kbd>${this.formatShortcut(key)}</kbd></td>
                    <td>${description}</td>
                </tr>
            `)
            .join('');

        return `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Atajos de teclado</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Atajo</th>
                                    <th>Descripción</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${shortcuts}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        `;
    }

    formatShortcut(shortcut) {
        return shortcut
            .replace('mod+', navigator.platform.includes('Mac') ? '⌘ + ' : 'Ctrl + ')
            .replace('shift+', 'Shift + ')
            .toUpperCase();
    }
}

// Add keyboard shortcuts styles
const style = document.createElement('style');
style.textContent = `
    .shortcuts-modal kbd {
        display: inline-block;
        padding: 3px 6px;
        font-family: monospace;
        line-height: 1.4;
        color: #444;
        background-color: #f7f7f7;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-shadow: 0 1px 0 rgba(0,0,0,0.2);
        margin: 0 2px;
    }

    .shortcuts-modal .table {
        margin-bottom: 0;
    }

    .shortcuts-modal .table td {
        vertical-align: middle;
    }
`;
document.head.appendChild(style);
