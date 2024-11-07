export const setupEventListeners = (editor) => {
    // Initialize all event listeners
    setupComponentEvents(editor);
    setupEditorEvents(editor);
    setupModalEvents(editor);
    setupKeyboardEvents(editor);
    setupPanelEvents(editor);
    setupDragAndDropEvents(editor);
};

const setupComponentEvents = (editor) => {
    editor.on('component:selected', (component) => {
        handleComponentSelection(editor, component);
    });

    editor.on('component:create', (component) => {
        handleComponentCreation(editor, component);
    });

    editor.on('component:update', (component) => {
        handleComponentUpdate(editor, component);
    });

    editor.on('component:styleUpdate', (component) => {
        handleStyleUpdate(editor, component);
    });

    editor.on('component:remove', (component) => {
        handleComponentRemoval(editor, component);
    });
};

const setupEditorEvents = (editor) => {
    editor.on('load', () => {
        initializeEditorState(editor);
    });

    editor.on('update', () => {
        handleEditorUpdate(editor);
    });

    editor.on('canvas:update', () => {
        handleCanvasUpdate(editor);
    });

    editor.on('storage:start', () => {
        showLoadingState();
    });

    editor.on('storage:end', () => {
        hideLoadingState();
    });
};

const setupModalEvents = (editor) => {
    // Exit Modal
    document.getElementById('exitConfirmModal')?.addEventListener('show.bs.modal', () => {
        updateVersionOptions('exit-version-type', editor);
    });

    document.getElementById('confirmExit')?.addEventListener('click', () => {
        handleExit(editor);
    });

    // Save Modal
    document.getElementById('saveModal')?.addEventListener('show.bs.modal', () => {
        updateVersionOptions('save-version-type', editor);
    });

    document.getElementById('confirm-save')?.addEventListener('click', () => {
        handleSave(editor);
    });

    // Code Editor Modal
    document.getElementById('codeEditorModal')?.addEventListener('show.bs.modal', () => {
        initializeCodeEditors(editor);
    });
};

const setupKeyboardEvents = (editor) => {
    document.addEventListener('keydown', (event) => {
        handleKeyboardShortcuts(event, editor);
    });
};

const setupPanelEvents = (editor) => {
    // Panel visibility toggles
    ['layers', 'styles', 'traits'].forEach(panelName => {
        document.getElementById(`show-${panelName}`)?.addEventListener('click', () => {
            togglePanel(editor, panelName);
        });
    });

    // Device preview toggles
    ['desktop', 'tablet', 'mobile'].forEach(device => {
        document.getElementById(`device-${device}`)?.addEventListener('click', () => {
            setDevicePreview(editor, device);
        });
    });
};

const setupDragAndDropEvents = (editor) => {
    editor.on('block:drag:start', (block) => {
        handleDragStart(editor, block);
    });

    editor.on('block:drag:stop', (component) => {
        handleDragStop(editor, component);
    });
};

// Event Handlers
function handleComponentSelection(editor, component) {
    updateTraits(editor, component);
    updateStylePanel(editor, component);
    updateSelectedState(component);
    showComponentOptions(editor, component);
}

function handleComponentCreation(editor, component) {
    ensureUniqueId(component);
    setupDefaultTraits(component);
    initializePluginIfNeeded(editor, component);
}

function handleComponentUpdate(editor, component) {
    updatePreview(editor);
    saveTemporaryState(editor);
}

function handleStyleUpdate(editor, component) {
    applyStylesToSharedClasses(editor, component);
    updateStyleManager(editor, component);
    saveTemporaryState(editor);
}

function handleComponentRemoval(editor, component) {
    cleanupComponent(editor, component);
    updatePreview(editor);
    saveTemporaryState(editor);
}

function handleEditorUpdate(editor) {
    saveTemporaryState(editor);
    updateUndoRedoButtons(editor);
}

function handleCanvasUpdate(editor) {
    updateResponsivePreview(editor);
    updateDeviceButtons(editor);
}

function handleKeyboardShortcuts(event, editor) {
    // Save: Ctrl/Cmd + S
    if ((event.ctrlKey || event.metaKey) && event.key === 's') {
        event.preventDefault();
        editor.runCommand('save-page');
    }

    // Undo: Ctrl/Cmd + Z
    if ((event.ctrlKey || event.metaKey) && event.key === 'z') {
        event.preventDefault();
        editor.UndoManager.undo();
    }

    // Redo: Ctrl/Cmd + Y or Ctrl/Cmd + Shift + Z
    if ((event.ctrlKey || event.metaKey) && (event.key === 'y' || (event.shiftKey && event.key === 'z'))) {
        event.preventDefault();
        editor.UndoManager.redo();
    }

    // Delete: Delete or Backspace
    if ((event.key === 'Delete' || event.key === 'Backspace') && editor.getSelected()) {
        const selectedComponent = editor.getSelected();
        if (!isEditingText(selectedComponent)) {
            event.preventDefault();
            selectedComponent.remove();
        }
    }
}

// Utility Functions
function ensureUniqueId(component) {
    if (!component.get('attributes').id) {
        component.addAttributes({
            id: `${component.get('type')}-${Math.random().toString(36).substring(2, 9)}`
        });
    }
}

function updateVersionOptions(selectId, editor) {
    const select = document.getElementById(selectId);
    if (select) {
        const currentVersion = window.editorConfig.pageData.version || '1.0.0';
        const [major, minor] = currentVersion.split('.');

        select.innerHTML = `
            ${selectId === 'exit-version-type' ? '<option value="none">No guardar</option>' : ''}
            <option value="current">Versión actual (${currentVersion})</option>
            <option value="minor">Versión menor (${major}.${parseInt(minor) + 1}.0)</option>
            <option value="major">Versión mayor (${parseInt(major) + 1}.0.0)</option>
        `;
    }
}

function togglePanel(editor, panelName) {
    editor.runCommand(`show-${panelName}`);
}

function setDevicePreview(editor, device) {
    editor.setDevice(
        device === 'desktop' ? 'Escritorio' :
            device === 'tablet' ? 'Tablet' : 'Móvil'
    );
}

function showLoadingState() {
    const loadingEl = document.createElement('div');
    loadingEl.className = 'editor-loading';
    loadingEl.innerHTML = '<div class="spinner"></div>';
    document.body.appendChild(loadingEl);
}

function hideLoadingState() {
    const loadingEl = document.querySelector('.editor-loading');
    if (loadingEl) {
        loadingEl.remove();
    }
}

function saveTemporaryState(editor) {
    localStorage.setItem('gjs-temporary-state', JSON.stringify({
        html: editor.getHtml(),
        css: editor.getCss(),
        components: editor.getComponents(),
        timestamp: Date.now()
    }));
}

function isEditingText(component) {
    return component && (
        component.get('type') === 'text' ||
        component.get('type') === 'link' ||
        component.view.el.contentEditable === 'true'
    );
}

export function initializeEditorState(editor) {
    // Set iframe dimensions
    const iframe = editor.Canvas.getFrameEl();
    if (iframe) {
        iframe.style.width = '100%';
        iframe.style.height = '100%';
    }

    // Load saved content
    if (window.editorConfig.serializedContent) {
        editor.setComponents(window.editorConfig.serializedContent);
    }

    // Set default styles
    editor.setStyle(window.editorConfig.defaultStyles);

    // Initialize active plugins
    if (window.editorConfig.activePlugins?.length > 0) {
        window.editorConfig.activePlugins.forEach(pluginId => {
            const plugin = window.editorConfig.availablePlugins.find(p => p.id === pluginId);
            if (plugin) {
                initializePlugin(editor, plugin);
            }
        });
    }

    // Set initial device
    editor.setDevice('Escritorio');
}
