export const initializePanelCommands = (editor) => {
    initializeLayersPanel(editor);
    initializeStylesPanel(editor);
    initializeTraitsPanel(editor);
};

function initializeLayersPanel(editor) {
    editor.Commands.add('show-layers', {
        getLayersEl(editor) {
            return editor.getContainer().closest('.editor-row')
                .querySelector('.panel__right #layers-container');
        },
        run(editor, sender) {
            const lmEl = this.getLayersEl(editor);
            showPanel(lmEl);
            highlightButton(sender);
        },
        stop(editor, sender) {
            const lmEl = this.getLayersEl(editor);
            hidePanel(lmEl);
            unhighlightButton(sender);
        }
    });
}

function initializeStylesPanel(editor) {
    editor.Commands.add('show-styles', {
        run(editor, sender) {
            const styleEl = getPanelElement(editor, '#styles-container');
            showPanel(styleEl);
            highlightButton(sender);
        },
        stop(editor, sender) {
            const styleEl = getPanelElement(editor, '#styles-container');
            hidePanel(styleEl);
            unhighlightButton(sender);
        }
    });
}

function initializeTraitsPanel(editor) {
    editor.Commands.add('show-traits', {
        run(editor, sender) {
            const traitEl = getPanelElement(editor, '#traits-container');
            showPanel(traitEl);
            highlightButton(sender);
        },
        stop(editor, sender) {
            const traitEl = getPanelElement(editor, '#traits-container');
            hidePanel(traitEl);
            unhighlightButton(sender);
        }
    });
}

// Utility functions
function getPanelElement(editor, selector) {
    return editor.getContainer()
        .closest('.editor-row')
        .querySelector(`.panel__right ${selector}`);
}

function showPanel(element) {
    if (element) {
        element.style.display = '';
    }
}

function hidePanel(element) {
    if (element) {
        element.style.display = 'none';
    }
}

function highlightButton(sender) {
    if (sender) {
        sender.set('active', 1);
    }
}

function unhighlightButton(sender) {
    if (sender) {
        sender.set('active', 0);
    }
}
