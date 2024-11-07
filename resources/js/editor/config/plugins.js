export const initializePluginSystem = (editor) => {
    const availablePlugins = window.editorConfig.availablePlugins;

    // Register plugin components
    availablePlugins.forEach(plugin => {
        registerPluginComponent(editor, plugin);
        addPluginBlock(editor, plugin);
    });

    // Handle plugin events
    setupPluginEvents(editor);
};

const registerPluginComponent = (editor, plugin) => {
    editor.Components.addType(plugin.name, {
        model: {
            defaults: {
                tagName: 'div',
                droppable: true,
                attributes: {
                    'data-gjs-type': plugin.name
                },
                traits: [{
                    type: 'select',
                    label: 'Vista',
                    name: 'view',
                    options: plugin.views.map(view => ({
                        value: view,
                        name: view.charAt(0).toUpperCase() + view.slice(1)
                    }))
                }]
            }
        },
        view: {
            async onRender() {
                try {
                    await renderPluginContent(this, plugin);
                } catch (error) {
                    renderPluginError(this, plugin, error);
                }
            }
        }
    });
};

const addPluginBlock = (editor, plugin) => {
    editor.BlockManager.add(plugin.name, {
        label: `
            <div class="plugin-block">
                <i class="fas fa-${plugin.icon || 'puzzle-piece'}"></i>
                <div>${plugin.original_name}</div>
            </div>
        `,
        content: { type: plugin.name },
        category: 'Plugins'
    });
};

const setupPluginEvents = (editor) => {
    editor.on('block:drag:stop', component => {
        const type = component.get('type');
        const plugin = window.editorConfig.availablePlugins.find(p => p.name === type);
        if (plugin) {
            component.getView().updateContent();
        }
    });

    editor.on('component:selected', component => {
        const type = component.get('type');
        const plugin = window.editorConfig.availablePlugins.find(p => p.name === type);
        if (plugin) {
            const view = component.getView();
            if (view) {
                view.updateContent();
            }
        }
    });
};

async function renderPluginContent(view, plugin) {
    const viewName = view.model.get('traits')
        .where({ name: 'view' })[0]?.get('value') || 'index';

    const response = await fetch(`/plugins/${plugin.id}/preview/${viewName}`);
    const data = await handlePluginResponse(response);

    if (data.success) {
        view.el.innerHTML = data.html;
        initializePluginScripts(view.el, plugin);
    } else {
        throw new Error(data.error || 'Error loading plugin content');
    }
}

function renderPluginError(view, plugin, error) {
    view.el.innerHTML = `
        <div class="plugin-error">
            <i class="fas fa-exclamation-triangle"></i>
            <p>Error loading ${plugin.original_name}</p>
            <small>${error.message}</small>
        </div>`;
}

async function handlePluginResponse(response) {
    const contentType = response.headers.get('content-type');
    if (contentType?.includes('application/json')) {
        return await response.json();
    }
    return {
        success: response.ok,
        html: await response.text(),
        error: response.ok ? null : 'Invalid response format'
    };
}

function initializePluginScripts(element, plugin) {
    const initFunctionName = `initialize${plugin.name}`;
    if (typeof window[initFunctionName] === 'function') {
        window[initFunctionName](element);
    }
}
