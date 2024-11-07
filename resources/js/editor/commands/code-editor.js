import { beautifyHTML, beautifyCSS } from '../utils/format';

export const initializeCodeEditorCommand = (editor) => {
    editor.Commands.add('edit-code', {
        run: function (editor) {
            const modal = createCodeEditorModal(editor);
            setupCodeEditors(modal, editor);
            setupEventListeners(modal, editor);
        }
    });
};

function createCodeEditorModal(editor) {
    const modal = new bootstrap.Modal(document.getElementById('codeEditorModal'));
    modal.show();
    return modal;
}

function setupCodeEditors(modal, editor) {
    const htmlContent = beautifyHTML(editor.getHtml());
    const cssContent = beautifyCSS(editor.getCss());

    // Initialize HTML Editor
    const htmlEditor = CodeMirror.fromTextArea(document.getElementById('html-editor'), {
        mode: 'htmlmixed',
        theme: 'monokai',
        lineNumbers: true,
        autoCloseTags: true,
        autoCloseBrackets: true,
        matchBrackets: true,
        indentUnit: 4,
        tabSize: 4,
        lineWrapping: true,
        foldGutter: true,
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
        extraKeys: {
            "Ctrl-Q": cm => cm.foldCode(cm.getCursor())
        },
        value: htmlContent
    });

    // Initialize CSS Editor
    const cssEditor = CodeMirror.fromTextArea(document.getElementById('css-editor'), {
        mode: 'css',
        theme: 'monokai',
        lineNumbers: true,
        autoCloseBrackets: true,
        matchBrackets: true,
        indentUnit: 4,
        tabSize: 4,
        lineWrapping: true,
        foldGutter: true,
        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
        extraKeys: {
            "Ctrl-Q": cm => cm.foldCode(cm.getCursor())
        },
        value: cssContent
    });

    return { htmlEditor, cssEditor };
}

function setupEventListeners(modal, editor) {
    const { htmlEditor, cssEditor } = editor.codeEditors;

    // Format buttons
    document.getElementById('format-html').addEventListener('click', () => {
        htmlEditor.setValue(beautifyHTML(htmlEditor.getValue()));
    });

    document.getElementById('format-css').addEventListener('click', () => {
        cssEditor.setValue(beautifyCSS(cssEditor.getValue()));
    });

    // Copy buttons
    document.getElementById('copy-html').addEventListener('click', () => {
        copyToClipboard(htmlEditor.getValue(), 'HTML copiado al portapapeles');
    });

    document.getElementById('copy-css').addEventListener('click', () => {
        copyToClipboard(cssEditor.getValue(), 'CSS copiado al portapapeles');
    });

    // Apply changes button
    document.getElementById('apply-code-changes').addEventListener('click', () => {
        applyChanges(editor, htmlEditor, cssEditor);
        modal.hide();
    });
}

function copyToClipboard(text, successMessage) {
    navigator.clipboard.writeText(text)
        .then(() => showNotification(successMessage, 'success'))
        .catch(err => showNotification('Error al copiar: ' + err.message, 'error'));
}

function applyChanges(editor, htmlEditor, cssEditor) {
    const htmlContent = htmlEditor.getValue();
    const cssContent = cssEditor.getValue();

    editor.setComponents(htmlContent);
    editor.setStyle(cssContent);
}

function showNotification(message, type = 'info') {
    // Implementar sistema de notificaciones
    const toast = document.createElement('div');
    toast.className = `editor-notification ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}
