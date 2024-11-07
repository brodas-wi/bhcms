export const initializeSaveCommand = (editor) => {
    editor.Commands.add('save-page', {
        run: async function (editor) {
            const pageData = collectPageData(editor);

            try {
                // Show save modal
                const saveModal = new bootstrap.Modal(document.getElementById('saveModal'));
                saveModal.show();

                // Handle save confirmation
                document.getElementById('confirm-save').onclick = async function () {
                    try {
                        const versionType = document.getElementById('save-version-type').value;
                        const response = await savePage({ ...pageData, version_type: versionType });

                        if (response.success) {
                            handleSaveSuccess(saveModal);
                        } else {
                            throw new Error(response.error || 'Error al guardar la página');
                        }
                    } catch (error) {
                        handleSaveError(error, saveModal);
                    }
                };
            } catch (error) {
                handleSaveError(error);
            }
        }
    });
};

function collectPageData(editor) {
    const components = editor.getComponents();
    const htmlContent = editor.getHtml();
    const cssStyles = editor.getCss();

    // Extract plugin information
    const usedPlugins = editor.getWrapper().find('[data-gjs-type]').map(component => {
        const type = component.get('type');
        const plugin = window.editorConfig.availablePlugins.find(p => p.name === type);
        if (plugin) {
            return {
                id: plugin.id,
                name: plugin.name,
                view: component.get('traits')?.where({ name: 'view' })[0]?.get('value') || 'index'
            };
        }
        return null;
    }).filter(Boolean);

    return {
        name: window.editorConfig.pageData.name,
        content: htmlContent,
        serialized_content: JSON.stringify(components),
        template_id: window.editorConfig.pageData.templateId,
        user_id: window.editorConfig.pageData.userId,
        status: 'draft',
        active_plugins: usedPlugins.map(p => p.id),
        plugin_data: usedPlugins
    };
}

async function savePage(pageData) {
    try {
        const response = await fetch(window.editorConfig.routes.save, {
            method: window.editorConfig.pageData.id ? 'PUT' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.editorConfig.csrf
            },
            body: JSON.stringify(pageData)
        });

        return await response.json();
    } catch (error) {
        throw new Error('Error en la conexión: ' + error.message);
    }
}

function handleSaveSuccess(saveModal) {
    saveModal.hide();
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();

    document.getElementById('successModalOk').onclick = function () {
        window.location.href = window.editorConfig.routes.index;
    };
}

function handleSaveError(error, saveModal) {
    if (saveModal) {
        saveModal.hide();
    }
    const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
    document.getElementById('errorModalBody').textContent = 'Error al guardar página: ' + error.message;
    errorModal.show();
}
